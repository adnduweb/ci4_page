<?php namespace Spreadaurora\ci4_page\Entities;

use CodeIgniter\Entity;

class Page extends Entity
{
    use \Tatter\Relations\Traits\EntityTrait;
    protected $table      = 'pages';
    protected $tableLang  = 'pages_langs';
    protected $primaryKey = 'id_page';

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



    public function setNameLang(int $id_lang)
    {
        return $this->pages_langs[$id_lang]->name ?? null;
    }

    public function setpagesLangs()
    {
        if (!empty($this->pages_langs)) {
            // unset($this->attributes[$this->tableLang][0]);
            $i = 0;
            foreach ($this->pages_langs as $lang) {
                $this->attributes[$this->tableLang][$lang->id_lang] = $lang;
                $i++;
            }
            unset($this->attributes[$this->tableLang][0]);
        }

        return $this->attributes;
    }


    public function _prepareLang()
    {
        $lang = [];
        if (!empty($this->id_page)) {
            foreach ($this->pages_langs as $tabs_lang) {
                $lang[$tabs_lang->id_lang] = $tabs_lang;
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
            $this->tableLang =  $builder->where(['id_lang' => $k, 'page_id_page' => $key])->get()->getRow();
            // print_r($this->tableLang);
            if (empty($this->tableLang)) {
                $data = [
                    'page_id_page'      => $key,
                    'id_lang'           => $k,
                    'name'              => $v['name'],
                    'description_short' => $v['description_short'],
                    'description'       => $v['description'],
                    'meta_title'        => $v['meta_title'],
                    'meta_description'  => $v['meta_description'],
                ];
                // Create the new participant
                $builder->insert($data);
            } else {
                $data = [
                    'page_id_page' => $this->tableLang->page_id_page,
                    'id_lang'      => $this->tableLang->id_lang,
                    'name'              => $v['name'],
                    'description_short' => $v['description_short'],
                    'description'       => $v['description'],
                    'meta_title'        => $v['meta_title'],
                    'meta_description'  => $v['meta_description'],
                ];
                print_r($data);
                $builder->set($data);
                $builder->where(['page_id_page' => $this->tableLang->page_id_page, 'id_lang' => $this->tableLang->id_lang]);
                $builder->update();
            }
        }
    }

}
