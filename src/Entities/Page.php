<?php

namespace Adnduweb\Ci4_page\Entities;

use CodeIgniter\Entity;

class Page extends Entity
{
    use \Tatter\Relations\Traits\EntityTrait;
    use \App\Traits\BuilderEntityTrait;
    protected $table          = 'pages';
    protected $tableLang      = 'pages_langs';
    protected $primaryKey     = 'id';
    protected $primaryKeyLang = 'page_id';

    protected $attributes = [
        'id'            => null,
        'id_parent'          => null,
        'template'           => null,
        'active'             => null,
        'no_follow_no_index' => null,
        'handle'             => null,
        'order'              => null,
        'created_at'         => null,
        'updated_at'         => null,
        'deleted_at'         => null
    ];


    protected $datamap = [];
    /**
     * Define properties that are automatically converted to Time instances.
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    /**
     * Array of field names and the type of value to cast them as
     * when they are accessed.
     */
    protected $casts = [];

    public function getIdPage()
    {
        return $this->attributes['id'] ?? null;
    }

    public function getSlug()
    {
        if (isset($this->pages_langs)) {
            foreach ($this->pages_langs as $lang) {
                if (service('switchlanguage')->getIdLocale() == $lang->id_lang) {
                    return $lang->slug;
                }
            }
        } else {
            return $this->attributes['slug'] ?? null;
        }
    }

    public function getNameAllLang()
    {
        $name = [];
        $i = 0;
        if (isset($this->pages_langs)) {
            foreach ($this->pages_langs as $lang) {
                $name[$lang->id_lang]['name'] = $lang->name;
                $i++;
            }
            return $name ?? null;
        } else {
            $name[service('switchlanguage')->getIdLocale()]['name'] =  $this->attributes['name'];
            return $name ?? null;
        }
    }


    public function _prepareLang()
    {
        $lang = [];
        if (!empty($this->id)) {
            foreach ($this->pages_langs as $tabs_langs) {
                $lang[$tabs_langs->id_lang] = $tabs_langs;
            }
        }
        return $lang;
    }

    public function saveLang(array $data, int $key)
    {
        //print_r($data);
        $db      = \Config\Database::connect();
        $builder = $db->table($this->tableLang);
        foreach ($data as $k => $v) {
            $this->tableLang =  $builder->where(['id_lang' => $k, $this->primaryKeyLang => $key])->get()->getRow();
            // print_r($this->tableLang);
            if (empty($this->tableLang)) {
                $data = [
                    $this->primaryKeyLang => $key,
                    'id_lang'             => $k,
                    'name'                => $v['name'],
                    'name_2'              => $v['name_2'],
                    'description_short'   => $v['description_short'],
                    'description'         => $v['description'],
                    'meta_title'          => $v['meta_title'],
                    'meta_description'    => $v['meta_description'],
                    'slug'                => uniforme(trim($v['slug'])),
                ];
                // Create the new participant
                $builder->insert($data);
            } else {
                $data = [
                    $this->primaryKeyLang => $this->tableLang->{$this->primaryKeyLang},
                    'id_lang'             => $this->tableLang->id_lang,
                    'name'                => $v['name'],
                    'name_2'              => $v['name_2'],
                    'description_short'   => $v['description_short'],
                    'description'         => $v['description'],
                    'meta_title'          => $v['meta_title'],
                    'meta_description'    => $v['meta_description'],
                    'slug'                => uniforme(trim($v['slug'])),
                ];
                //print_r($data);
                $builder->set($data);
                $builder->where([$this->primaryKeyLang => $this->tableLang->{$this->primaryKeyLang}, 'id_lang' => $this->tableLang->id_lang]);
                $builder->update();
            }
        }
    }

    // public function getDescription()
    // {
    //     foreach ($this->pages_langs as $lang) {
    //         if (service('switchlanguage')->getIdLocale() == $lang->id_lang) {
    //             return $lang->description ?? null;
    //         }
    //     }
    // }

    // public function getDescriptionShort()
    // {
    //     foreach ($this->pages_langs as $lang) {
    //         if (service('switchlanguage')->getIdLocale() == $lang->id_lang) {
    //             return $lang->description_short ?? null;
    //         }
    //     }
    // }

    // public function get_MetaDescription()
    // {
    //     foreach ($this->pages_langs as $lang) {
    //         if (service('switchlanguage')->getIdLocale() == $lang->id_lang) {
    //             return $lang->meta_description ?? null;
    //         }
    //     }
    // }

    // public function get_MetaTitle()
    // {
    //     foreach ($this->pages_langs as $lang) {
    //         if (service('switchlanguage')->getIdLocale() == $lang->id_lang) {
    //             return $lang->meta_title ?? null;
    //         }
    //     }
    // }

    // public function getName()
    // {
    //     if (isset($this->pages_langs)) {
    //         foreach ($this->pages_langs as $lang) {
    //             if (service('switchlanguage')->getIdLocale() == $lang->id_lang) {
    //                 return $lang->name;
    //             }
    //         }
    //     } else {
    //         return $this->attributes['name'] ?? null;
    //     }
    // }
    // public function getClassEntities()
    // {
    //     return $this->table;
    // }
}
