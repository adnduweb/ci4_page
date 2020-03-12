<?php namespace Spreadaurora\ci4_page\Controllers\Front;

use CodeIgniter\API\ResponseTrait;
use Spreadaurora\ci4_page\Models\PagesModel;

class FrontPagesController extends \App\Controllers\Front\FrontController
{
    use ResponseTrait;
    
    public function __construct()
    {
        parent::__construct();
        $this->tableModel  = new PagesModel();
    }
    public function index()
    {
        
    }

    public function show($id)
    { 
        $loccale = 1;
        //print_r(service('Settings')->setting_supportedLocales);
        $setting_supportedLocales = unserialize(service('Settings')->setting_supportedLocales);
        foreach($setting_supportedLocales as $setting_supportedLocale){
            $v= explode('|', $setting_supportedLocale);
            if($this->request->getLocale() == $v[1]){
                $loccale = $v[0];
            }
        }
        //$page = $this->tableModel->join('pages_langs', 'pages.id_page = pages_langs.page_id_page')->where(['id_lang'=> service('Settings')->setting_id_lang, 'slug'=> '/'.$id])->getCompiledSelect();
        $this->data['page'] = $this->tableModel->join('pages_langs', 'pages.id_page = pages_langs.page_id_page')->where(['id_lang'=>$loccale, 'slug'=> '/'.$id])->get()->getRow();
        if(empty($this->data['page'])){
            throw new \CodeIgniter\Exceptions\PageNotFoundException(lang('Core.Cannot find the page item : {0}', [$id]));
        }
        //print_r($this->data['page']); exit;
        $this->data['no_follow_no_index'] = ($this->data['page']->no_follow_no_index == 0) ?  'index follow' :  'no-index no-follow';
        $this->data['id']  = str_replace('/', '', $this->data['page']->slug);
        $this->data['class'] = $this->data['class'] . ' ' .  str_replace('/', '', $this->data['page']->slug) . ' ' .  str_replace('/', '', $this->data['page']->template);
        $this->data['meta_title'] = $this->data['page']->meta_title;
        $this->data['meta_description'] = $this->data['page']->meta_description;
        //print_r($page); exit;
        if($this->data['page']->template =='code'){
            return view($this->get_current_theme_view($this->data['page']->slug, 'default'), $this->data);
        }else{
            return view($this->get_current_theme_view('page', 'Spreadaurora/ci4_page'), $this->data);
        }
        
    }

}
