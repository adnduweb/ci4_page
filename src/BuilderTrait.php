<?php

namespace Spreadaurora\ci4_page;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Config\Services;
use Spreadaurora\ci4_page\Entities\Builder;
use Spreadaurora\ci4_page\Models\BuildersModel;
use App\Exceptions\DataException;

trait BuilderTrait
{

    public $compoments = [];

    public $compoment = [];

    public $builder = true;


    public function saveBuilder($builder)
    {

        $buildersModel = new BuildersModel();

        if (!is_array($builder))
            return false;

        unset($builder['__field__']);
        $i = 0;
        $instance = [];
        foreach ($builder as $build) {
            $lang = (isset($build['lang'])) ? $build['lang'] : '';
            unset($build['lang']);
            $instance[] = $builderEntitie = new Builder($build);
            $builderEntitie->handle = strtolower(preg_replace('/[^a-zA-Z0-9\-]/', '', preg_replace('/\s+/', '-', $builderEntitie->handle)));
            if ($builderEntitie->type == 'imagefield') {
                $getAttrOptions = $builderEntitie->getAttrOptions();
                print_r($getAttrOptions->media->filename);
                if (!get_file_info($getAttrOptions->media->filename)) {
                    $builderEntitie->options = '';
                } else {
                    list($width, $height, $type, $attr) =  getimagesize($getAttrOptions->media->filename);
                    $getAttrOptions->media->dimensions = ['width' => $width, 'height' => $height];
                    $builderEntitie->options = json_encode($getAttrOptions);
                }
            }

            if (!$buildersModel->save($builderEntitie)) {
                throw DataException::forProblemSaving($buildersModel->errors(true));
            }
            $id_builder = (!isset($builderEntitie->id_builder)) ? $buildersModel->insertID() : $builderEntitie->id_builder;
            if (!empty($lang)) {
                if ($builderEntitie->saveLang($lang, $id_builder)) {
                    throw DataException::forProblemSaving($buildersModel->errors(true));
                }
            }

            $i++;
        }


        return $instance;
    }

    public function getBuilderIdPage(int $id_page)
    {
        $buildersModel = new BuildersModel();
        return $buildersModel->getBuilderIdPage($id_page);
    }

    public function deleteBuilder(int $id_builder)
    {
        $buildersModel = new BuildersModel();
        return $buildersModel->delete(['id_builder' => $id_builder]);
    }
}
