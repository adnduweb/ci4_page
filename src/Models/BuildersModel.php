<?php

namespace Spreadaurora\ci4_page\Models;

use CodeIgniter\Model;
use Spreadaurora\ci4_page\Entities\Builder;

class BuildersModel extends Model
{
    use \Tatter\Relations\Traits\ModelTrait;
    use \Spreadaurora\Ci4_logs\Traits\AuditsTrait;
    protected $afterInsert = ['auditInsert'];
    protected $afterUpdate = ['auditUpdate'];
    protected $afterDelete = ['auditDelete'];

    protected $table = 'builders';
    protected $tableLang = 'builders_langs';
    protected $with = ['builders_langs'];
    protected $without = [];
    protected $primaryKey = 'id_builder';
    protected $returnType = Builder::class;
    protected $useSoftDeletes = false;
    protected $allowedFields = ['page_id_page', 'id_field', 'handle', 'class', 'id', 'type', 'options', 'order'];
    protected $useTimestamps = true;
    protected $validationRules = ['page_id_page' => 'required'];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function __construct()
    {
        parent::__construct();
        $this->builder = $this->db->table('builders');
        $this->builder_langs = $this->db->table('builders_langs');
    }

    /* @Todo */
    //Voir le module de relation @Tatter
    public function tmpReset()
    {
    }


    public function getBuilderIdPage(int $idPage)
    {
        $instance = [];
        $this->builder->select();
        $this->builder->join($this->tableLang, $this->table . '.' . $this->primaryKey . ' = ' . $this->tableLang . '.builder_id_builder');
        $this->builder->where('deleted_at IS NULL AND id_lang = ' . service('settings')->setting_id_lang);
        $this->builder->orderBy('id_builder DESC');
        // echo $this->builder->getCompiledSelect();
        // exit;
        $builders = $this->builder->get()->getResult();
        if (!empty($builders)) {
            foreach ($builders as $builder) {
                $instance[] = new Builder((array) $builder);
            }
        }
        //echo $this->builder->getCompiledSelect(); exit;
        return $instance;
    }
}
