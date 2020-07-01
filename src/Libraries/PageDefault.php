<?php

namespace  Adnduweb\Ci4_page\Libraries;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Config\Services;
use App\Exceptions\DataException;

class PageDefault
{

    public $title;
    public $description;
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

        /** 
     *
     * l'ID du module
     */
    public function getIdItem()
    {
        return md5($this->meta_title);
    }

    public function getBMetaTitle()
    {
        return $this->meta_title;
    }

    public function getBMetaDescription()
    {
        return $this->meta_description;
    }

    public function getBName()
    {
        return $this->title;
    }

    public function getBDescription()
    {
        return $this->description;
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
