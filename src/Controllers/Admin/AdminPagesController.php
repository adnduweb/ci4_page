<?php

namespace Adnduweb\Ci4_page\Controllers\Admin;

use App\Controllers\Admin\AdminController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

use App\Libraries\AssetsBO;
use App\Libraries\Tools;
use Adnduweb\Ci4_page\Entities\Page;
use Adnduweb\Ci4_page\Models\PagesModel;



class AdminPagesController extends AdminController
{

    use \App\Traits\BuilderTrait;
    use \App\Traits\ModuleTrait;

    /**
     *  * @var Module */
    public $module      = true;
    public $name_module = 'pages';
    protected $idModule;
    public $controller = 'pages';
    public $item = 'page';
    public $type = 'Adnduweb/Ci4_page';
    public $pathcontroller  = '/public/pages';
    public $fieldList = 'name';
    public $add = true;
    public $multilangue = true;


    public function __construct()
    {
        parent::__construct();
        $this->controller_type = 'adminpages';
        $this->module = "pages";
        $this->tableModel  = new PagesModel();
        $this->idModule  = $this->getIdModule();
    }

    public function renderViewList()
    {
        //print_r(Service('currency')->Taxe());exit;
        AssetsBO::add_js([$this->get_current_theme_view('controllers/' . $this->controller . '/js/list.js', 'default')]);
        $parent =  parent::renderViewList();
        if (is_object($parent) && $parent->getStatusCode() == 307) {
            return $parent;
        }
        return $parent;
    }

    public function ajaxProcessList()
    {
        $parent = parent::ajaxProcessList();
        return $this->respond($parent, 200, lang('Core.liste des taxes'));
    }

    public function renderForm($id = null)
    {
        AssetsBO::add_js([$this->get_current_theme_view('plugins/custom/ckeditor/ckeditor-classic.bundle.js', 'default')]);
        AssetsBO::add_js([$this->get_current_theme_view('controllers/medias/js/manager.js', 'default')]);
        AssetsBO::add_js([$this->get_current_theme_view('js/builder.js', 'default')]);
        if (class_exists('\Adnduweb\Ci4_blog\Controllers\Admin\AdminArticleController'))
            AssetsBO::add_js([$this->get_current_theme_view('controllers/blog/js/builder.js', 'default')]);
        if (class_exists('\Adnduweb\Ci4_diaporama\Controllers\Admin\AdminDiaporamasController'))
            AssetsBO::add_js([$this->get_current_theme_view('controllers/diaporamas/js/builder.js', 'default')]);
        AssetsBO::add_js([$this->get_current_theme_view('controllers/' . $this->controller . '/js/outils.js', 'default')]);

        if (is_null($id)) {
            $this->data['form'] = new Page($this->request->getPost());
        } else {
            $this->data['form'] = $this->tableModel->where('id_page', $id)->first();
            if (empty($this->data['form'])) {
                Tools::set_message('danger', lang('Core.not_{0}_exist', [$this->item]), lang('Core.warning_error'));
                return redirect()->to('/' . env('CI_SITE_AREA') . '/public/pages');
            }
        }
        $this->data['form']->builders = [];
        $this->data['form']->id_module = $this->idModule;
        $this->data['form']->id_item = $id;
        if (!empty($this->getBuilderIdItem($id, $this->idModule))) {
            $this->data['form']->builders = $this->getBuilderIdItem($id, $this->idModule);
            $temp = [];
            foreach ($this->data['form']->builders as $builder) {
                $temp[$builder->order] = $builder;
            }
            ksort($temp);
            $this->data['form']->builders = $temp;
        }
        //print_r($this->data['form']->builders); exit;
        parent::renderForm($id);
        return view($this->get_current_theme_view('form', 'Adnduweb/Ci4_page'), $this->data);
    }

    public function postProcessEdit($param)
    {
        // validate
        $page = new PagesModel();
        $this->validation->setRules(['lang.1.slug' => 'required']);
        if (!$this->validation->run($this->request->getPost())) {
            Tools::set_message('danger', $this->validation->getErrors(), lang('Core.warning_error'));
            return redirect()->back()->withInput();
        }

        // Try to create the user
        $pageBase = new Page($this->request->getPost());
        // print_r($_POST);
        // print_r($pageBase);
        // exit;

        $pageBase->active = $this->request->getPost('active') ? 1 : 0;
        $this->lang = $this->request->getPost('lang');

        //On Créer un template si besoin
        if ($pageBase->template == 'code') {
            $file =  $pageBase->id_page == '1' ? 'home' :  $pageBase->handle;
            if ($file == 'home') {
                if (!file_exists(APPPATH . 'Views/front/themes/' . service('settings')->setting_theme_front . '/' . $file . '.php')) {
                    rename(APPPATH . 'Views/front/themes/' . service('settings')->setting_theme_front . '/home.php', APPPATH . 'Views/front/themes/' . service('settings')->setting_theme_front . '/__home.php',);
                }
            }
            if (!file_exists(APPPATH . 'Views/front/themes/' . service('settings')->setting_theme_front . '/' . $file . '.php')) {
                write_file(APPPATH . 'Views/front/themes/' . service('settings')->setting_theme_front . '/' . $file . '.php', '<!-- Votre code -->');
            }
        }


        if (!$page->save($pageBase)) {
            Tools::set_message('danger', $page->errors(), lang('Core.warning_error'));
            return redirect()->back()->withInput();
        }
        $pageBase->saveLang($this->lang, $pageBase->id_page);

        // On enregistre le Builder si existe
        $this->saveBuilder($this->request->getPost('builder'));

        // Success!
        Tools::set_message('success', lang('Core.save_data'), lang('Core.cool_success'));
        $redirectAfterForm = [
            'url'                   => '/' . env('CI_SITE_AREA') . '/public/pages',
            'action'                => 'edit',
            'submithandler'         => $this->request->getPost('submithandler'),
            'id'                    => $pageBase->id_page,
        ];

        $this->videCache($pageBase->id_page);

        $this->redirectAfterForm($redirectAfterForm);
    }

    public function postProcessAdd()
    {
        // validate
        $page = new PagesModel();
        $this->validation->setRules(['lang.1.slug' => 'required']);
        if (!$this->validation->run($this->request->getPost())) {
            Tools::set_message('danger', $this->validation->getErrors(), lang('Core.warning_error'));
            return redirect()->back()->withInput();
        }

        // Try to create the user
        $pageBase = new Page($this->request->getPost());
        $pageBase->active = $this->request->getPost('active') ? 1 : 0;
        $pageBase->handle = uniforme(trim($this->request->getPost('lang[1][slug]')));

        if (!$page->save($pageBase)) {
            Tools::set_message('danger', $page->errors(), lang('Core.warning_error'));
            return redirect()->back()->withInput();
        }

        $pageBaseId = $page->insertID();
        $this->lang = $this->request->getPost('lang');
        $pageBase->saveLang($this->lang, $pageBaseId);

        //On Créer un template si besoin
        if ($pageBase->template == 'code') {
            $file =  $pageBase->handle;
            if (!file_exists(APPPATH . 'Views/front/themes/' . service('settings')->setting_theme_front . '/' . $file . '.php')) {
                write_file(APPPATH . 'Views/front/themes/' . service('settings')->setting_theme_front . '/' . $file . '.php', '<!-- Votre code -->');
            }

            //$pageBase->template = "default";
        }

        // Success!
        Tools::set_message('success', lang('Core.save_data'), lang('Core.cool_success'));
        $redirectAfterForm = [
            'url'                   => '/' . env('CI_SITE_AREA') . '/public/pages',
            'action'                => 'add',
            'submithandler'         => $this->request->getPost('submithandler'),
            'id'                    => $pageBaseId,
        ];
        $this->redirectAfterForm($redirectAfterForm);
    }

    public function ajaxProcessUpdate()
    {
        if ($value = $this->request->getPost('value')) {
            $data = [];
            if (isset($value['selected']) && !empty($value['selected'])) {
                $homePage = false;
                foreach ($value['selected'] as $selected) {

                    if ($selected == '1') {
                        $homePage = true;
                        break;
                    }

                    $data[] = [
                        'id_page'      => $selected,
                        'active' => $value['active'],
                    ];
                }
            }
            if ($homePage == true) {
                return $this->respond(['status' => false, 'database' => true, 'display' => 'modal', 'message' => lang('Js.aucun_enregistrement_effectue')], 200);
            } else {
                if ($this->tableModel->updateBatch($data, 'id_page')) {
                    return $this->respond(['status' => true, 'message' => lang('Js.your_seleted_records_statuses_have_been_updated')], 200);
                } else {
                    return $this->respond(['status' => false, 'database' => true, 'display' => 'modal', 'message' => lang('Js.aucun_enregistrement_effectue')], 200);
                }
            }
        }
    }

    public function ajaxProcessDeleteBuilder()
    {
        if ($value = $this->request->getPost('value')) {
            $this->deleteBuilder($value);
            return $this->respond(['status' => true, 'type' => 'success', 'message' => lang('Js.your_selected_records_have_been_deleted')], 200);
        }
    }

    public function ajaxProcessDelete()
    {
        if ($value = $this->request->getPost('value')) {
            if (!empty($value['selected'])) {
                $itsme = false;
                foreach ($value['selected'] as $id) {

                    $this->tableModel->delete($id);
                }
                return $this->respond(['status' => true, 'type' => 'success', 'message' => lang('Js.your_selected_records_have_been_deleted')], 200);
            }
        }
        return $this->failUnauthorized(lang('Js.not_autorized'), 400);
    }

    public function ajaxProcessVideCache()
    {
        if ($value = $this->request->getPost('value')) {
            $this->videCache($value['id_page']);
            return $this->respond(['status' => true, 'type' => 'success', 'message' => lang('Core.cache_page_vide')], 200);
        }
        return $this->failUnauthorized(lang('Js.not_autorized'), 400);
    }

    protected function videCache(int $id_page)
    {
        foreach (glob(WRITEPATH . 'cache/pages:' . $id_page . '*') as $file) {
            //echo $file; exit;
            cache()->delete(str_replace(WRITEPATH . 'cache/', '', $file));
        }
    }
}
