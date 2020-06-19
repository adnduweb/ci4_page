<?php

namespace Adnduweb\Ci4_page\Controllers\Front;

use CodeIgniter\API\ResponseTrait;
use Adnduweb\Ci4_page\Entities\Page;
use Adnduweb\Ci4_page\Models\PageModel;

class FrontPagesController extends \App\Controllers\Front\FrontController
{
    use \App\Traits\BuilderModelTrait;
    use \App\Traits\ModuleTrait;

    public $name_module = 'pages';
    protected $slugModule;
    protected $page;

    public function __construct()
    {
        parent::__construct();
        $this->tableModel  = new PageModel();
        $this->idModule  = $this->getIdModule();
    }
    public function index()
    {
    }

    public function show($slug)
    {

        $locale = 1;
        $setting_supportedLocales = unserialize(service('Settings')->setting_supportedLocales);
        foreach ($setting_supportedLocales as $setting_supportedLocale) {
            $v = explode('|', $setting_supportedLocale);
            if ($this->request->getLocale() == $v[1]) {
                $locale = $v[0];
            }
        }

        $pageLight = $this->tableModel->getIdPageBySlug($slug);
        if (empty($pageLight)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException(lang('Core.Cannot find the page item : {0}', [$slug]));
        }
        $this->data['page'] = $this->tableModel->where('id', $pageLight->id)->first();

        $this->data['no_follow_no_index'] = ($this->data['page']->no_follow_no_index == 0) ?  'index follow' :  'no-index no-follow';
        $this->data['id']  = str_replace('/', '', $this->data['page']->slug);
        $this->data['class'] = $this->data['class'] . ' ' .  str_replace('/', '', $this->data['page']->slug) . ' ' .  str_replace('/', '', $this->data['page']->template) . ' page_' . $this->data['page']->id;
        $this->data['meta_title'] = $this->data['page']->meta_title;
        $this->data['meta_description'] = $this->data['page']->meta_description;
        $builders = $this->getBuilderIdItem($this->data['page']->id, $this->idModule);
        if (!empty($builders)) {
            $this->data['page']->builders = $builders;
            $temp = [];
            foreach ($this->data['page']->builders as $builder) {
                $temp[$builder->order] = $builder;
            }
            ksort($temp);
            $this->data['page']->builders = $temp;
        }

        // print_r($builders);
        // exit;

        if ($this->data['page']->template == 'code') {
            return view($this->get_current_theme_view($this->data['page']->handle, 'default'), $this->data);
        } else {
            return view($this->get_current_theme_view('__template_part/' . $this->data['page']->template, 'default'), $this->data);
        }
    }
}
