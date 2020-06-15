<?php

namespace  Adnduweb\Ci4_page\Libraries;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Config\Services;
use App\Exceptions\DataException;

class PageDefault
{

    public $title;
    public $meta_title;
    public $meta_description;
    public $url;

    public $no_follow_no_index;

    public function __construct($page)
    {
        // print_r($page);
        // exit;
        $this->title = $page['title'];
        $this->meta_title = $page['meta_title'];
        $this->meta_description = $page['meta_description'];
        $this->url = $page['url'];
    }

    public function get_MetaTitle()
    {
        return $this->meta_title;
    }

    public function get_MetaDescription()
    {
        return $this->meta_description;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getStwichLangSlug()
    {
        $lang = [];
        foreach ($this->url as $k => $v) {
            $lang[$k] = (object) ['slug' => $v['slug']];
        }
        return $lang;
    }

    public function getSlug($id_lang)
    {
        foreach ($this->url as $k => $v) {
            if ($k == $id_lang) {
                return $v['slug'];
            }
        }
    }

    public function getClassEntities()
    {
        return 'pagesDefault';
    }
}
