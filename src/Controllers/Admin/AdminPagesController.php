<?php

namespace Spreadaurora\Ci4_page\Controllers\Admin;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

use App\Libraries\AssetsBO;
use Spreadaurora\Ci4_page\Entities\Page;
use Spreadaurora\Ci4_page\Models\PagesModel;

class AdminPagesController extends \App\controllers\Admin\AdminController
{
    /**
     *  * @var Module */
    public $module = true;
    public $type = 'Spreadaurora/Ci4_page';
    public $controller = 'pages';
    public $item = 'page';
    public $pathcontroller  = '/public/pages';
    public $fieldList = 'name';
    public $add = true;
    public $multilangue = true;


    public function __construct()
    {
        parent::__construct();
        $this->controller_type = 'adminpages';
        $this->module = "pages";
        $this->tableModel  = new PagesModel();
    }

    // public function index(){
    //     return view($this->get_current_theme_view('index', 'Spreadaurora/Ci4_page'), $this->data);
    // }

    public function renderViewList()
    {
        //print_r(Service('currency')->Taxe());exit;
        AssetsBO::add_js([$this->get_current_theme_view('controllers/' . $this->controller . '/js/list.js', 'default')]);
        $parent =  parent::renderViewList();
        if (is_object($parent) && $parent->getStatusCode() == 307) {
            return $parent;
        }
        return $parent;
    }

    public function ajaxProcessList()
    {
        $parent = parent::ajaxProcessList();
        return $this->respond($parent, 200, lang('Core.liste des taxes'));
    }


}
