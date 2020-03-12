<?php

namespace Spreadaurora\ci4_page\Controllers\Admin;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

use App\Libraries\AssetsBO;
use App\Libraries\Tools;
use Spreadaurora\ci4_page\Entities\Page;
use Spreadaurora\ci4_page\Models\PagesModel;

class AdminPagesController extends \App\controllers\Admin\AdminController
{
    /**
     *  * @var Module */
    public $module = true;
    public $controller = 'pages';
    public $item = 'page';
    public $type = 'Spreadaurora/ci4_page';
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
        if (is_null($id)) {
            $this->data['form'] = new Page($this->request->getPost());
        } else {
            $this->data['form'] = $this->tableModel->where('id_page', $id)->first();
            if (empty($this->data['form'])) {
                Tools::set_message('danger', lang('Core.not_{0}_exist', [$this->item]), lang('Core.warning_error'));
                return redirect()->to('/' . env('CI_SITE_AREA') . '/' . user()->id_company . '/public/pages');
            }
        }
        parent::renderForm($id);
        return view($this->get_current_theme_view('form', 'Spreadaurora/ci4_page'), $this->data);
    }

    public function postProcessEdit($param)
    {
        // validate
        $page = new PagesModel();
        $rules = [
            'slug' => 'required',
        ];
        if (!$this->validate($rules)) {
            Tools::set_message('danger', $this->validator->getErrors(), lang('Core.warning_error'));
            return redirect()->back()->withInput();
        }

        // Try to create the user
        $pageBase = new Page($this->request->getPost());
        $this->lang = $this->request->getPost('lang');
        $pageBase->slug = "/" . strtolower(preg_replace('/[^a-zA-Z0-9\-]/', '', preg_replace('/\s+/', '-', $pageBase->slug)));

        if (!$page->save($pageBase)) {
            Tools::set_message('danger', $page->errors(), lang('Core.warning_error'));
            return redirect()->back()->withInput();
        }
        $pageBase->saveLang($this->lang, $pageBase->id_page);

        //On Créer un teamplet si besoin
        if($pageBase->template == 'code'){
            if (!file_exists(APPPATH . 'Views/Front/Themes/'.service('settings')->setting_theme_front.'/' . $pageBase->slug .'.php')) {
                write_file(APPPATH . 'Views/Front/Themes/'.service('settings')->setting_theme_front.'/' . $pageBase->slug .'.php', '<!-- Votre code -->');
            }
        }


        // Success!
        Tools::set_message('success', lang('Core.save_data'), lang('Core.cool_success'));
        $redirectAfterForm = [
            'url'                   => '/' . env('CI_SITE_AREA') . '/' . user()->id_company . '/public/pages',
            'action'                => 'edit',
            'submithandler'         => $this->request->getPost('submithandler'),
            'id'                    => $pageBase->id_page,
        ];
        $this->redirectAfterForm($redirectAfterForm);
    }

    public function postProcessAdd()
    {
        // validate
        $page = new PagesModel();
        $rules = [
            'slug' => 'required',
        ];
        if (!$this->validate($rules)) {
            Tools::set_message('danger', $this->validator->getErrors(), lang('Core.warning_error'));
            return redirect()->back()->withInput();
        }

        // Try to create the user
        $pageBase = new Page($this->request->getPost());

        if (!$page->save($pageBase)) {
            Tools::set_message('danger', $page->errors(), lang('Core.warning_error'));
            return redirect()->back()->withInput();
        }

        $pageBaseId = $page->insertID();
        $this->lang = $this->request->getPost('lang');
        $pageBase->saveLang($this->lang, $pageBaseId);

        // Success!
        Tools::set_message('success', lang('Core.save_data'), lang('Core.cool_success'));
        $redirectAfterForm = [
            'url'                   => '/' . env('CI_SITE_AREA') . '/' . user()->id_company . '/public/pages',
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
}
