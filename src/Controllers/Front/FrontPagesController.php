<?php

namespace Spreadaurora\ci4_page\Controllers\Front;

use CodeIgniter\API\ResponseTrait;
use Spreadaurora\ci4_page\Entities\Page;
use Spreadaurora\ci4_page\Models\PagesModel;

class FrontPagesController extends \App\Controllers\Front\FrontController
{
    use \Spreadaurora\ci4_page\BuilderTrait;

    public function __construct()
    {
        parent::__construct();
        $this->tableModel  = new PagesModel();
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
        // print_r($this->data['page']);
        // exit;
        if ($this->data['page']->template == 'code') {
            return view($this->get_current_theme_view($this->data['page']->slug, 'default'), $this->data);
        } else {
            return view($this->get_current_theme_view('page', 'Spreadaurora/ci4_page'), $this->data);
        }
    }
}
