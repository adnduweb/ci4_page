<?php

namespace Adnduweb\Ci4_page\Models;

use CodeIgniter\Model;
use Adnduweb\Ci4_page\Entities\Page;

class PagesModel extends Model
{
    use \Tatter\Relations\Traits\ModelTrait;
    use \Adnduweb\Ci4_logs\Traits\AuditsTrait;
    protected $afterInsert = ['auditInsert'];
    protected $afterUpdate = ['auditUpdate'];
    protected $afterDelete = ['auditDelete'];

    protected $table = 'pages';
    protected $tableLang = 'pages_langs';
    protected $with = ['pages_langs'];
    protected $without = [];
    protected $primaryKey = 'id_page';
    protected $returnType = Page::class;
    protected $useSoftDeletes = true;
    protected $allowedFields = ['id_parent', 'template', 'active', 'no_follow_no_index', 'slug', 'order'];
    protected $useTimestamps = true;
    protected $validationRules = [
        'slug'            => 'required'
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function __construct()
    {
        parent::__construct();
        $this->page = $this->db->table('pages');
    }

    public function getListByMenu()
    {
        $instance = [];
        $this->page->select('id_page, slug, name, created_at');
        $this->page->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.page_id_page');
        $this->page->where('deleted_at IS NULL AND id_lang = ' . service('settings')->setting_id_lang);
        $this->page->orderBy('id_page DESC');
        $pages = $this->page->get()->getResult();
        if (!empty($pages)) {
            foreach ($pages as $page) {
                $instance[] = new Page((array) $page);
            }
        }
        //echo $this->page->getCompiledSelect(); exit;
        return $instance;
    }




    public function getAllList(int $page, int $perpage, array $sort, array $query)
    {
        $this->page->select();
        $this->page->select('created_at as date_create_at');
        $this->page->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.page_id_page');
        if (isset($query['generalSearch']) && !empty($query['generalSearch'])) {
            $this->page->where('deleted_at IS NULL AND (name LIKE "%' . $query['generalSearch'] . '%" OR login_destination LIKE "%' . $query['generalSearch'] . '%") AND id_lang = ' . service('settings')->setting_id_lang);
            $this->page->limit(0, $page);
        } else {
            $this->page->where('deleted_at IS NULL AND id_lang = ' . service('settings')->setting_id_lang);
            $page = ($page == '1') ? '0' : (($page - 1) * $perpage);
            $this->page->limit($perpage, $page);
        }


        $this->page->orderBy($sort['field'] . ' ' . $sort['sort']);

        $groupsRow = $this->page->get()->getResult();

        //echo $this->page->getCompiledSelect(); exit;
        return $groupsRow;
    }

    public function getAllCount(array $sort, array $query)
    {
        $this->page->select($this->table . '.' . $this->primaryKey);
        $this->page->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.page_id_page');
        if (isset($query['generalSearch']) && !empty($query['generalSearch'])) {
            $this->page->where('deleted_at IS NULL AND (name LIKE "%' . $query['generalSearch'] . '%" OR login_destination LIKE "%' . $query['generalSearch'] . '%") AND id_lang = ' . service('settings')->setting_id_lang);
        } else {
            $this->page->where('deleted_at IS NULL AND id_lang = ' . service('settings')->setting_id_lang);
        }

        $this->page->orderBy($sort['field'] . ' ' . $sort['sort']);

        $pages = $this->page->get();
        //echo $this->page->getCompiledSelect(); exit;
        return $pages->getResult();
    }
}
