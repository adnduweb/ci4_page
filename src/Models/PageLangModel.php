<?php

namespace Adnduweb\Ci4_page\Models;

use CodeIgniter\Model;

class PageLangModel extends Model
{
    protected $table          = 'pages_langs';
    protected $tableLang      = 'pages_langs';
    protected $primaryKey     = 'page_id';
    protected $returnType     = 'object';
    protected $skipValidation     = false;

}
