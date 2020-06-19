<?php

namespace Adnduweb\Ci4_page\Models;

use CodeIgniter\Model;
use Adnduweb\Ci4_page\Entities\Page;

class PageModel extends Model
{
    use \Tatter\Relations\Traits\ModelTrait;
    use \Adnduweb\Ci4_logs\Traits\AuditsTrait;
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
        'id_parent', 'template', 'active', 'no_follow_no_index', 'handle', 'order'
    ];
    protected $useTimestamps      = true;
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function __construct()
    {
        parent::__construct();
        $this->pages_table = $this->db->table('pages');
        $this->pages_table_lang = $this->db->table('pages_langs');
    }

    public function getAllPageOptionParent()
    {
        $instance = [];
        $this->pages_table->select($this->table . '.id, slug, name, id_parent, created_at');
        $this->pages_table->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.' . $this->primaryKeyLang);
        $this->pages_table->where('deleted_at IS NULL AND id_lang = ' . service('settings')->setting_bo_id_lang);
        $this->pages_table->orderBy($this->table . '.id DESC');
        $page = $this->pages_table->get()->getResult();
        //echo $this->pages_table->getCompiledSelect(); exit;
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
        $this->pages_table->select($this->table . '.id, slug, name, created_at');
        $this->pages_table->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.' . $this->primaryKeyLang);
        $this->pages_table->where('deleted_at IS NULL AND id_lang = ' . service('settings')->setting_id_lang);
        $this->pages_table->orderBy($this->table . '.id DESC');
        $page = $this->pages_table->get()->getResult();
        if (!empty($page)) {
            foreach ($page as $page) {
                $instance[] = new Page((array) $page);
            }
        }
        //echo $this->pages_table->getCompiledSelect(); exit;
        return $instance;
    }

    public function getAllList(int $page, int $perpage, array $sort, array $query)
    {
        $this->pages_table->select();
        $this->pages_table->select('created_at as date_create_at');
        $this->pages_table->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.' . $this->primaryKeyLang);
        if (isset($query[0]) && is_array($query)) {
            $this->pages_table->where('deleted_at IS NULL AND (name LIKE "%' . $query[0] . '%" OR description_short LIKE "%' . $query[0] . '%") AND id_lang = ' . service('settings')->setting_id_lang);
            $this->pages_table->limit(0, $page);
        } else {
            $this->pages_table->where('deleted_at IS NULL AND id_lang = ' . service('settings')->setting_id_lang);
            $page = ($page == '1') ? '0' : (($page - 1) * $perpage);
            $this->pages_table->limit($perpage, $page);
        }


        $this->pages_table->orderBy($sort['field'] . ' ' . $sort['sort']);

        $groupsRow = $this->pages_table->get()->getResult();

        //echo $this->pages_table->getCompiledSelect(); exit;
        return $groupsRow;
    }

    public function getAllCount(array $sort, array $query)
    {
        $this->pages_table->select($this->table . '.' . $this->primaryKey);
        $this->pages_table->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.' . $this->primaryKeyLang);
        if (isset($query[0]) && is_array($query)) {
            $this->pages_table->where('deleted_at IS NULL AND (name LIKE "%' . $query[0] . '%" OR description_short LIKE "%' . $query[0] . '%") AND id_lang = ' . service('settings')->setting_id_lang);
        } else {
            $this->pages_table->where('deleted_at IS NULL AND id_lang = ' . service('settings')->setting_id_lang);
        }

        $this->pages_table->orderBy($sort['field'] . ' ' . $sort['sort']);

        $page = $this->pages_table->get();
        //echo $this->pages_table->getCompiledSelect(); exit;
        return $page->getResult();
    }

    public function getPageBySlug($slug)
    {
        $this->pages_table->select();
        $this->pages_table->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.' . $this->primaryKeyLang);
        $this->pages_table->where('deleted_at IS NULL AND slug="' . $slug . '"');
        $page = $this->pages_table->get()->getRowArray();
        if ($page['active'] == '1')
            return $page;
        return false;
    }

    public function getIdPageBySlug($slug)
    {
        $this->pages_table->select($this->table . '.' . $this->primaryKey . ', active');
        $this->pages_table->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.' . $this->primaryKeyLang);
        $this->pages_table->where('deleted_at IS NULL AND  slug="' . $slug . '"');
        $page = $this->pages_table->get()->getRow();
        if (!empty($page)) {
            if ($page->active == '1')
                return $page;
        }
        return false;
    }

    public function getPageBreadcrumbBySlug($slug)
    {
        $this->pages_table->select('name');
        $this->pages_table->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.' . $this->primaryKeyLang);
        $this->pages_table->where('deleted_at IS NULL AND active =1 AND slug="' . $slug . '"');
        $page = $this->pages_table->get()->getRow();
        if (!empty($page)) {
            return $page->name;
        }
        return false;
    }



    public function getPageByIdInMenu($id, int $id_lang)
    {
        $this->pages_table->select();
        $this->pages_table->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.' . $this->primaryKeyLang);
        $this->pages_table->where([$this->table . '.id' => $id, 'id_lang' => $id_lang]);
        $page = $this->pages_table->get()->getRow();
        if (!empty($page)) {
            if ($page->active == '1') {
                return $page;
            }
        }
        return false;
    }

    public function getLink(int $id, int $id_lang)
    {
        $this->pages_table->select('slug, id_parent');
        $this->pages_table->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.' . $this->primaryKeyLang);
        $this->pages_table->where([$this->table . '.id' => $id, 'id_lang' => $id_lang]);
        $page = $this->pages_table->get()->getRow();
        return $page;
    }
    public function getLinkBySlug(string $handle)
    {
        $this->pages_table->select('slug, id_parent');
        $this->pages_table->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.' . $this->primaryKeyLang);
        $this->pages_table->where([$this->table . '.handle' => $handle, 'id_lang' => service('switchlanguage')->getIdLocale()]);
        $page = $this->pages_table->get()->getRow();
        return $page;
    }
}
