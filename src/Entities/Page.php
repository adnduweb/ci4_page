<?php

namespace Adnduweb\Ci4_page\Entities;

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

    public function getId()
    {
        return $this->id_page ?? null;
    }
    public function getName()
    {
        return $this->attributes['name'] ?? null;
    }
    
    public function getNameLang(int $id_lang)
    {
        foreach ($this->pages_langs as $lang) {
            if ($id_lang == $lang->id_lang) {
                return $lang->name ?? null;
            }
        }
    }

    public function getSousNameLang(int $id_lang)
    {
        foreach ($this->pages_langs as $lang) {
            if ($id_lang == $lang->id_lang) {
                return $lang->name_2 ?? null;
            }
        }
    }

    public function getDescription(int $id_lang)
    {
        foreach ($this->pages_langs as $lang) {
            if ($id_lang == $lang->id_lang) {
                return $lang->description ?? null;
            }
        }
    }

    public function get_MetaDescription(int $id_lang)
    {
        foreach ($this->pages_langs as $lang) {
            if ($id_lang == $lang->id_lang) {
                return $lang->meta_description ?? null;
            }
        }
    }

    public function get_MetaTitle(int $id_lang)
    {
        foreach ($this->pages_langs as $lang) {
            if ($id_lang == $lang->id_lang) {
                return $lang->meta_title ?? null;
            }
        }
    }

    public function getBuilder(string $id_field, int $id_lang)
    {
        foreach ($this->builders as $builder) {
            if ($id_field == $builder->id_field) {
                foreach ($builder->builders_langs as $lang) {
                    if ($id_lang == $lang->id_lang) {
                        $builder->id_lang = $lang->id_lang;
                        $builder->content = $lang->content;
                    }
                }
                unset($builder->builders_langs);
                return $builder ?? null;
            }
            return false;
        }
    }

    public function getBuilderContent(string $id_field, int $id_lang)
    {
        if (!empty($this->builders)) {
            foreach ($this->builders as $builder) {
                if ($id_field == $builder->id_field) {
                    foreach ($builder->builders_langs as $lang) {
                        if ($id_lang == $lang->id_lang) {
                            return $lang->content ?? null;
                        }
                    }
                }
                return null;
            }
            return null;
        }
    }

    public function getTextarea(string $handle, int $id_lang)
    {
        if (!empty($this->builders)) {
            $i = 0;
            foreach ($this->builders as $builder) {
                if ($handle == $builder->handle && $builder->type == "textarea") {
                    foreach ($builder->builders_langs as $lang) {
                        if ($id_lang == $lang->id_lang) {
                            return $lang->content ?? null;
                        }
                    }
                }
                $i++;
            }
            return null;
        }
    }
    public function getTitle(string $handle, int $id_lang)
    {
        if (!empty($this->builders)) {
            $i = 0;
            foreach ($this->builders as $builder) {
                if ($handle == $builder->handle && $builder->type == "textfield") {
                    foreach ($builder->builders_langs as $lang) {
                        if ($id_lang == $lang->id_lang) {
                            return $lang->content ?? null;
                        }
                    }
                }
                $i++;
            }
            return null;
        }
    }

    public function getLink($id_lang){
        foreach ($this->pages_langs as $lang) {
            if ($id_lang == $lang->id_lang) {
                return $lang->slug ?? null;
            }
        }
    }

    public function getLangsLink(){
        $lang = [];
        if (!empty($this->id_page)) {
            foreach ($this->pages_langs as $tabs_lang) {
                $lang[$tabs_lang->id_lang] = $tabs_lang;
            }
        }
        return $lang;
    }

    public function getBundleActu(string $handle, int $id_lang)
    {
        $listActu = new \stdClass();
        $articles = [];
        if (!empty($this->builders)) {
            $i = 0;

            foreach ($this->builders as $builder) {
                if ($handle == $builder->handle && $builder->type == "actufield") {

                    $getAttrOptions = $builder->getAttrOptions();
                    if (empty($getAttrOptions))
                        return $listActu->options = $getAttrOptions;

                    $articlesModel = new \Adnduweb\Ci4_blog\Models\ArticlesModel();
                    $categoriesModel = new \Adnduweb\Ci4_blog\Models\CategoriesModel();
                    if ($getAttrOptions->cat == 'all') {
                            $articles = $articlesModel->where('type', 1)->get()->getResult('array');
                    } else {
                        // $listActu = $categoriesModel->where('id_media', $getAttrOptions->media->id_media)->get()->getRow();
                    }

                 //   print_r($articles); exit;

                    if (!empty($articles)) {
                        $i = 0;
                        foreach ($articles as $actu) {
                            $listActu->articles[$i] = new \Adnduweb\Ci4_blog\Entities\Article($actu);
                            $listActu->articles[$i]->categorie = $categoriesModel->find($actu['id_categorie_default']);
                            $i++;
                        }
                    }


                    if (is_object($listActu)) {
                        $listActu->class = $builder->class . ' actu ';
                        $listActu->id = $builder->id;
                        $listActu->options = $getAttrOptions;
                    }
                }
                $i++;
            }
        }

        return $listActu;
    }

    public function getImage(string $handle, int $id_lang)
    {
        $image = null;
        if (!empty($this->builders)) {
            $i = 0;

            foreach ($this->builders as $builder) {
                if ($handle == $builder->handle && $builder->type == "imagefield") {

                    $getAttrOptions = $builder->getAttrOptions();
                    if (empty($getAttrOptions))
                        return $image;

                    $mediasModel = new \App\Models\mediasModel();
                    $image = $mediasModel->getMediaById($getAttrOptions->media->id_media, $id_lang);
                    if (empty($image)) {
                        $image = $mediasModel->where('id_media', $getAttrOptions->media->id_media)->get()->getRow();
                    }
                    if (is_object($image)) {
                        $image->class = $builder->class . ' adw_lazyload ';
                        $image->id = $builder->id;
                        $image->options = $getAttrOptions;
                    }
                }
                $i++;
            }
        }

        //print_r($image); exit;

        return $image;
    }


    public function getNameAllLang()
    {
        $name = [];
        $i = 0;
        foreach ($this->pages_langs as $lang) {
            $name[$lang->id_lang]['name'] = $lang->name;
            $i++;
        }
        return $name ?? null;
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
            $this->tableLang =  $builder->where(['id_lang' => $k, 'id_page' => $key])->get()->getRow();
            // print_r($this->tableLang);
            if (empty($this->tableLang)) {
                $data = [
                    'id_page'           => $key,
                    'id_lang'           => $k,
                    'name'              => $v['name'],
                    'name_2'            => $v['name_2'],
                    'description_short' => $v['description_short'],
                    'description'       => $v['description'],
                    'meta_title'        => $v['meta_title'],
                    'meta_description'  => $v['meta_description'],
                    'slug'              => uniforme(trim($v['slug'])),
                ];
                // Create the new participant
                $builder->insert($data);
            } else {
                $data = [
                    'id_page'           => $this->tableLang->id_page,
                    'id_lang'           => $this->tableLang->id_lang,
                    'name'              => $v['name'],
                    'name_2'            => $v['name_2'],
                    'description_short' => $v['description_short'],
                    'description'       => $v['description'],
                    'meta_title'        => $v['meta_title'],
                    'meta_description'  => $v['meta_description'],
                    'slug'              => uniforme(trim($v['slug'])),
                ];
                //print_r($data);
                $builder->set($data);
                $builder->where(['id_page' => $this->tableLang->id_page, 'id_lang' => $this->tableLang->id_lang]);
                $builder->update();
            }
        }
    }
}
