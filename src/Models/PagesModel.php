<?php

namespace Spreadaurora\ci4_page\Models;

use CodeIgniter\Model;
use Spreadaurora\ci4_page\Entities\Page;

class PagesModel extends Model
{
    use \Tatter\Relations\Traits\ModelTrait;
    use \Spreadaurora\Ci4_logs\Traits\AuditsTrait;
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
        $this->taxe = $this->db->table('pages');
    }


    public function getAllList(int $page, int $perpage, array $sort, array $query)
    {
        $this->taxe->select(); 
        $this->taxe->select('created_at as date_create_at');
        $this->taxe->join($this->tableLang, $this->table . '.'.$this->primaryKey.' = '.$this->tableLang.'.page_id_page');
        if (isset($query['generalSearch']) && !empty($query['generalSearch'])) {
            $this->taxe->where('deleted_at IS NULL AND (name LIKE "%' . $query['generalSearch'] . '%" OR login_destination LIKE "%' . $query['generalSearch'] . '%") AND id_lang = ' . service('settings')->setting_id_lang);
            $this->taxe->limit(0, $page);
        } else {
            $this->taxe->where('deleted_at IS NULL AND id_lang = ' . service('settings')->setting_id_lang);
            $page = ($page == '1') ? '0' : (($page - 1) * $perpage);
            $this->taxe->limit($perpage, $page);
        }


        $this->taxe->orderBy($sort['field'] . ' ' . $sort['sort']);

        $groupsRow = $this->taxe->get()->getResult();

        //echo $this->taxe->getCompiledSelect(); exit;
        return $groupsRow;
    }

    public function getAllCount(array $sort, array $query)
    {
        $this->taxe->select($this->table . '.'.$this->primaryKey);
        $this->taxe->join($this->tableLang, $this->table . '.'.$this->primaryKey.' = '.$this->tableLang.'.page_id_page');
        if (isset($query['generalSearch']) && !empty($query['generalSearch'])) {
            $this->taxe->where('deleted_at IS NULL AND (name LIKE "%' . $query['generalSearch'] . '%" OR login_destination LIKE "%' . $query['generalSearch'] . '%") AND id_lang = ' . service('settings')->setting_id_lang);
        } else {
            $this->taxe->where('deleted_at IS NULL AND id_lang = ' . service('settings')->setting_id_lang);
        }

        $this->taxe->orderBy($sort['field'] . ' ' . $sort['sort']);

        $pages = $this->taxe->get();
        //echo $this->taxe->getCompiledSelect(); exit;
        return $pages->getResult();
    }
}
