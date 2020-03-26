<?php

namespace Spreadaurora\ci4_page\Entities;

use CodeIgniter\Entity;

class Builder extends Entity
{
    use \Tatter\Relations\Traits\EntityTrait;
    protected $table      = 'builders';
    protected $tableLang  = 'builders_langs';
    protected $primaryKey = 'id_builder';

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

    public function getId_builder()
    {
        return $this->attributes['id_builder'] ?? null;
    }

    public function getIdPage()
    {
        return  $this->attributes['id_builder'] ?? null;
    }

    public function getName()
    {
        return $this->attributes['name'] ?? null;
    }

    public function getAttrClass()
    {
        return $this->attributes['class'] ?? null;
    }

    public function getAttrOptions()
    {
        if (!empty($this->attributes['options'])) {
            return json_decode($this->attributes['options']);
        }
        return null;
    }

    public function getAttrId()
    {
        return $this->attributes['id'] ?? null;
    }

    public function getNameAllLang()
    {
        $name = [];
        $i = 0;
        foreach ($this->builders_langs as $lang) {
            $name[$lang->id_lang]['content'] = $lang->name;
            $i++;
        }
        return $name ?? null;
    }

    public function _prepareLang()
    {
        $lang = [];
        if (!empty($this->id_builder)) {
            foreach ($this->builders_langs as $tabs_lang) {
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
            $this->tableLang =  $builder->where(['id_lang' => $k, 'builder_id_builder' => $key])->get()->getRow();
            // print_r($this->tableLang);
            if (empty($this->tableLang)) {
                $data = [
                    'builder_id_builder' => $key,
                    'id_lang'            => $k,
                    'content'            => $v['content'],
                ];
                // Create the new participant
                $builder->insert($data);
            } else {
                $data = [
                    'builder_id_builder' => $this->tableLang->builder_id_builder,
                    'id_lang'            => $this->tableLang->id_lang,
                    'content'            => $v['content'],
                ];
                print_r($data);
                $builder->set($data);
                $builder->where(['builder_id_builder' => $this->tableLang->builder_id_builder, 'id_lang' => $this->tableLang->id_lang]);
                $builder->update();
            }
        }
    }
}
