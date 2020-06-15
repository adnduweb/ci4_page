<?php

namespace Adnduweb\Ci4_page\Models;

use CodeIgniter\Model;
use Adnduweb\Ci4_page\Entities\Page;

class PagesModel extends Model
{
    use \Tatter\Relations\Traits\ModelTrait;
    use \Adnduweb\Ci4_logs\Traits\AuditsTrait;
    protected $afterInsert        = ['auditInsert'];
    protected $afterUpdate        = ['auditUpdate'];
    protected $afterDelete        = ['auditDelete'];
    protected $table              = 'page';
    protected $tableLang          = 'page_lang';
    protected $with               = ['page_lang'];
    protected $without            = [];
    protected $primaryKey         = 'id_page';
    protected $returnType         = Page::class;
    protected $useSoftDeletes     = true;
    protected $allowedFields      = [
        'id_parent', 'template', 'active', 'no_follow_no_index', 'handle', 'order'
    ];
    protected $useTimestamps      = true;
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function __construct()
    {
        parent::__construct();
        $this->page_table = $this->db->table('page');
        $this->page_table_lang = $this->db->table('page_lang');
    }

    public function getAllPageOptionParent()
    {
        $instance = [];
        $this->page_table->select($this->table . '.id_page, slug, name, id_parent, created_at');
        $this->page_table->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.id_page');
        $this->page_table->where('deleted_at IS NULL AND id_lang = ' . service('settings')->setting_bo_id_lang);
        $this->page_table->orderBy($this->table . '.id_page DESC');
        $page = $this->page_table->get()->getResult();
        //echo $this->page_table->getCompiledSelect(); exit;
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
        $this->page_table->select($this->table . '.id_page, slug, name, created_at');
        $this->page_table->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.id_page');
        $this->page_table->where('deleted_at IS NULL AND id_lang = ' . service('settings')->setting_id_lang);
        $this->page_table->orderBy($this->table . '.id_page DESC');
        $page = $this->page_table->get()->getResult();
        if (!empty($page)) {
            foreach ($page as $page) {
                $instance[] = new Page((array) $page);
            }
        }
        //echo $this->page_table->getCompiledSelect(); exit;
        return $instance;
    }

    public function getAllList(int $page, int $perpage, array $sort, array $query)
    {
        $this->page_table->select();
        $this->page_table->select('created_at as date_create_at');
        $this->page_table->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.id_page');
        if (isset($query[0]) && is_array($query)) {
            $this->page_table->where('deleted_at IS NULL AND (name LIKE "%' . $query[0] . '%" OR description_short LIKE "%' . $query[0] . '%") AND id_lang = ' . service('settings')->setting_id_lang);
            $this->page_table->limit(0, $page);
        } else {
            $this->page_table->where('deleted_at IS NULL AND id_lang = ' . service('settings')->setting_id_lang);
            $page = ($page == '1') ? '0' : (($page - 1) * $perpage);
            $this->page_table->limit($perpage, $page);
        }


        $this->page_table->orderBy($sort['field'] . ' ' . $sort['sort']);

        $groupsRow = $this->page_table->get()->getResult();

        //echo $this->page_table->getCompiledSelect(); exit;
        return $groupsRow;
    }

    public function getAllCount(array $sort, array $query)
    {
        $this->page_table->select($this->table . '.' . $this->primaryKey);
        $this->page_table->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.id_page');
        if (isset($query[0]) && is_array($query)) {
            $this->page_table->where('deleted_at IS NULL AND (name LIKE "%' . $query[0] . '%" OR description_short LIKE "%' . $query[0] . '%") AND id_lang = ' . service('settings')->setting_id_lang);
        } else {
            $this->page_table->where('deleted_at IS NULL AND id_lang = ' . service('settings')->setting_id_lang);
        }

        $this->page_table->orderBy($sort['field'] . ' ' . $sort['sort']);

        $page = $this->page_table->get();
        //echo $this->page_table->getCompiledSelect(); exit;
        return $page->getResult();
    }

    public function getPageBySlug($slug)
    {
        $this->page_table->select();
        $this->page_table->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.id_page');
        $this->page_table->where('deleted_at IS NULL AND slug="' . $slug . '"');
        $page = $this->page_table->get()->getRowArray();
        if ($page['active'] == '1')
            return $page;
        return false;
    }

    public function getIdPageBySlug($slug)
    {
        $this->page_table->select($this->table . '.' . $this->primaryKey . ', active');
        $this->page_table->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.id_page');
        $this->page_table->where('deleted_at IS NULL AND  slug="' . $slug . '"');
        $page = $this->page_table->get()->getRow();
        if (!empty($page)) {
            if ($page->active == '1')
                return $page;
        }
        return false;
    }

    public function getPageBreadcrumbBySlug($slug)
    {
        $this->page_table->select('name');
        $this->page_table->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.id_page');
        $this->page_table->where('deleted_at IS NULL AND active =1 AND slug="' . $slug . '"');
        $page = $this->page_table->get()->getRow();
        if (!empty($page)) {
            return $page->name;
        }
        return false;
    }



    public function getPageByIdInMenu($id, int $id_lang)
    {
        $this->page_table->select();
        $this->page_table->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.id_page');
        $this->page_table->where([$this->table . '.id_page' => $id, 'id_lang' => $id_lang]);
        $page = $this->page_table->get()->getRow();
        if (!empty($page)) {
            if ($page->active == '1') {
                return $page;
            }
        }
        return false;
    }

    public function getLink(int $id_page, int $id_lang)
    {
        $this->page_table->select('slug, id_parent');
        $this->page_table->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.id_page');
        $this->page_table->where([$this->table . '.id_page' => $id_page, 'id_lang' => $id_lang]);
        $page = $this->page_table->get()->getRow();
        return $page;
    }
}
