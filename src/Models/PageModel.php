<?php

namespace Adnduweb\Ci4_page\Models;

use CodeIgniter\Model;
use Adnduweb\Ci4_page\Entities\Page;

class PageModel extends Model
{
    use \Tatter\Relations\Traits\ModelTrait, \Adnduweb\Ci4_logs\Traits\AuditsTrait, \App\Models\BaseModel;

    protected $afterInsert    = ['auditInsert'];
    protected $afterUpdate    = ['auditUpdate'];
    protected $afterDelete    = ['auditDelete'];
    protected $table          = 'pages';
    protected $tableLang      = 'pages_langs';
    protected $with           = ['pages_langs'];
    protected $without        = [];
    protected $primaryKey     = 'id';
    protected $primaryKeyLang = 'page_id';
    protected $returnType     = Page::class;
    protected $useSoftDeletes = true;
    protected $allowedFields  = [
        'id_parent', 'template', 'active', 'visible_title', 'no_follow_no_index', 'handle', 'order'
    ];
    protected $useTimestamps      = true;
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    protected $searchKtDatatable  = ['name', 'description_short', 'created_at'];

    public function __construct()
    {
        parent::__construct();
        $this->builder      = $this->db->table('pages');
        $this->builder_lang = $this->db->table('pages_langs');
    }

    public function getAllPageOptionParent()
    {
        $instance = [];
        $this->builder->select($this->table . '.id, slug, name, id_parent, created_at');
        $this->builder->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.' . $this->primaryKeyLang);
        $this->builder->where('deleted_at IS NULL AND id_lang = ' . service('switchlanguage')->getIdLocale());
        $this->builder->orderBy($this->table . '.id DESC');
        $page = $this->builder->get()->getResult();
        //echo $this->builder->getCompiledSelect(); exit;
        if (!empty($page)) {
            foreach ($page as $page) {
                $instance[] = new Page((array) $page);
            }
        }
        return $instance;
    }


    public function getListByMenu()
    {
        $instance = [];
        $this->builder->select($this->table . '.id, slug, name, created_at');
        $this->builder->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.' . $this->primaryKeyLang);
        $this->builder->where('deleted_at IS NULL AND id_lang = ' . service('switchlanguage')->getIdLocale());
        $this->builder->orderBy($this->table . '.id DESC');
        $page = $this->builder->get()->getResult();
        if (!empty($page)) {
            foreach ($page as $page) {
                $instance[] = new Page((array) $page);
            }
        }
        //echo $this->builder->getCompiledSelect(); exit;
        return $instance;
    }

    public function getAllList(int $page, int $perpage, array $sort, array $query)
    {
        $pages = $this->getBaseAllList($page, $perpage, $sort, $query, $this->searchKtDatatable);

        // In va chercher les b_categories_table
        if (!empty($pages)) {
            $i = 0;
            foreach ($pages as $page) {
                $LangueDisplay = [];
                foreach (service('switchlanguage')->getArrayLanguesSupported() as $k => $v) {
                    if ($page->id_lang == $v) {
                        //Existe = 
                        $LangueDisplay[$k] = true;
                    } else {
                        $LangueDisplay[$k] = false;
                    }
                }
                $pages[$i]->languages = $LangueDisplay;
                $i++;
            }
        }

        //echo $this->b_posts_table->getCompiledSelect(); exit;
        return $pages;
    }

    public function getPageBySlug($slug)
    {
        $this->builder->select();
        $this->builder->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.' . $this->primaryKeyLang);
        $this->builder->where('deleted_at IS NULL AND slug="' . $slug . '"');
        $page = $this->builder->get()->getRowArray();
        if ($page['active'] == '1')
            return $page;
        return false;
    }

    public function getIdPageBySlug($slug)
    {
        $this->builder->select($this->table . '.' . $this->primaryKey . ', active');
        $this->builder->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.' . $this->primaryKeyLang);
        $this->builder->where('deleted_at IS NULL AND  slug="' . $slug . '"');
        $page = $this->builder->get()->getRow();
        if (!empty($page)) {
            if ($page->active == '1')
                return $page;
        }
        return false;
    }

    public function getPageBreadcrumbBySlug($slug)
    {
        $this->builder->select('name');
        $this->builder->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.' . $this->primaryKeyLang);
        $this->builder->where('deleted_at IS NULL AND active =1 AND slug="' . $slug . '"');
        $page = $this->builder->get()->getRow();
        if (!empty($page)) {
            return $page->name;
        }
        return false;
    }



    public function getPageByIdInMenu($id, int $id_lang)
    {
        $this->builder->select();
        $this->builder->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.' . $this->primaryKeyLang);
        $this->builder->where([$this->table . '.id' => $id, 'id_lang' => $id_lang]);
        $page = $this->builder->get()->getRow();
        if (!empty($page)) {
            if ($page->active == '1') {
                return $page;
            }
        }
        return false;
    }

    public function getLink(int $id, int $id_lang)
    {
        $this->builder->select('slug, id_parent');
        $this->builder->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.' . $this->primaryKeyLang);
        $this->builder->where([$this->table . '.id' => $id, 'id_lang' => $id_lang]);
        $page = $this->builder->get()->getRow();
        return $page;
    }
    public function getLinkBySlug(string $handle)
    {
        $this->builder->select('slug, id_parent');
        $this->builder->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.' . $this->primaryKeyLang);
        $this->builder->where([$this->table . '.handle' => $handle, 'id_lang' => service('switchlanguage')->getIdLocale()]);
        $page = $this->builder->get()->getRow();
        return $page;
    }
}
