<?php

namespace Spreadaurora\ci4_page\Controllers\Front;

use CodeIgniter\API\ResponseTrait;
use Spreadaurora\ci4_page\Entities\Page;
use Spreadaurora\ci4_page\Models\PagesModel;

class FrontPagesController extends \App\Controllers\Front\FrontController
{
    use \App\Traits\BuilderTrait;
    use \App\Traits\ModuleTrait;
    
    public $name_module = 'pages';
    protected $idModule;

    public function __construct()
    {
        parent::__construct();
        $this->tableModel  = new PagesModel();
        $this->idModule  = $this->getIdModule();
    }
    public function index()
    {
    }

    public function show($id)
    {
        $loccale = 1;
        $setting_supportedLocales = unserialize(service('Settings')->setting_supportedLocales);
        foreach ($setting_supportedLocales as $setting_supportedLocale) {
            $v = explode('|', $setting_supportedLocale);
            if ($this->request->getLocale() == $v[1]) {
                $loccale = $v[0];
            }
        }
        $this->data['page'] = $this->tableModel->where(['slug' => '/' . $id])->first();
        if (empty($this->data['page'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException(lang('Core.Cannot find the page item : {0}', [$id]));
        }

        $this->data['no_follow_no_index'] = ($this->data['page']->no_follow_no_index == 0) ?  'index follow' :  'no-index no-follow';
        $this->data['id']  = str_replace('/', '', $this->data['page']->slug);
        $this->data['class'] = $this->data['class'] . ' ' .  str_replace('/', '', $this->data['page']->slug) . ' ' .  str_replace('/', '', $this->data['page']->template);
        $this->data['meta_title'] = $this->data['page']->meta_title;
        $this->data['meta_description'] = $this->data['page']->meta_description;
        $this->data['pageContent'] = $this->data['page'];
        $this->data['pageContent']->builders = [];
        if (!empty($this->getBuilderIdItem($this->data['page']->id_page, $this->idModule))) {
            $this->data['form']->builders = $this->getBuilderIdItem($id, $this->idModule);
            $temp = [];
            foreach ($this->data['pageContent']->builders as $builder) {
                $temp[$builder->order] = $builder;
            }
            ksort($temp);
            $this->data['pageContent']->builders = $temp;
        }

        if ($this->data['page']->template == 'code') {
            return view($this->get_current_theme_view($this->data['page']->slug, 'default'), $this->data);
        } else {
            return view($this->get_current_theme_view('page', 'Spreadaurora/ci4_page'), $this->data);
        }
    }
}
