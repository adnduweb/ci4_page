<?php

namespace Spreadaurora\ci4_page\Commands;

use Config\Autoload;
use CodeIgniter\CLI\CLI;
use CodeIgniter\CLI\BaseCommand;

class Publish extends BaseCommand
{
    /**
     * The group the command is lumped under
     * when listing commands.
     *
     * @var string
     */
    protected $group = 'SpreadAurora';

    /**
     * The Command's name
     *
     * @var string
     */
    protected $name = 'ci4_page:publish';

    /**
     * the Command's short description
     *
     * @var string
     */
    protected $description = 'Publish selected pages functionality into the current application.';

    /**
     * the Command's usage
     *
     * @var string
     */
    protected $usage = 'ci4_page:publish';

    /**
     * the Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * the Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * The path to Myth\Auth\src directory.
     *
     * @var string
     */
    protected $sourcePath;

    //--------------------------------------------------------------------

    /**
     * Displays the help for the spark cli script itself.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        $this->determineSourcePath();

        // Migration
        if (CLI::prompt('Publish css/js?', ['y', 'n']) == 'y') {
            $this->publishCssJs();
        }

        // Models
        if (CLI::prompt('Publish into directory Modules?', ['y', 'n']) == 'y') {
             $this->publishModules();
        }
    }


    protected function publishCssJs()
    {
        if ($this->copydir(
            $this->sourcePath . '/Assets/Admin/Themes/' . env('app.themeAdmin') . '/controllers/pages',
            ROOTPATH . '/public/Admin/Themes/' . env('app.themeAdmin') . '/controllers/pages'
        ) == true) {
            CLI::write('Les fichiers css et js du module ont été crées', 'green');
        }
    }

    protected function publishModules()
    {

        $this->copydir($this->sourcePath,APPPATH . '/Modules/ci4_page');

        CLI::write('Le Module a été chargé dans l\'application', 'green');

        // $models = ['LoginModel', 'UserModel'];

        // foreach ($models as $model)
        // {
        //     $path = "{$this->sourcePath}/Models/{$model}.php";

        //     $content = file_get_contents($path);
        //     $content = $this->replaceNamespace($content, 'Myth\Auth\Models', 'Models');

        //     $this->writeFile("Models/{$model}.php", $content);
        // }
    }


    //--------------------------------------------------------------------
    // Utilities
    //--------------------------------------------------------------------

    /**
     * Replaces the Myth\Auth namespace in the published
     * file with the applications current namespace.
     *
     * @param string $contents
     * @param string $originalNamespace
     * @param string $newNamespace
     *
     * @return string
     */
    protected function replaceNamespace(string $contents, string $originalNamespace, string $newNamespace): string
    {
        $appNamespace = APP_NAMESPACE;
        $originalNamespace = "namespace {$originalNamespace}";
        $newNamespace = "namespace {$appNamespace}\\{$newNamespace}";

        return str_replace($originalNamespace, $newNamespace, $contents);
    }

    /**
     * Determines the current source path from which all other files are located.
     */
    protected function determineSourcePath()
    {
        $this->sourcePath = realpath(__DIR__ . '/../');

        if ($this->sourcePath == '/' || empty($this->sourcePath)) {
            CLI::error('Unable to determine the correct source directory. Bailing.');
            exit();
        }
    }

    /**
     * Write a file, catching any exceptions and showing a
     * nicely formatted error.
     *
     * @param string $path
     * @param string $content
     */
    protected function writeFile(string $path, string $content)
    {
        $config = new Autoload();
        $appPath = $config->psr4[APP_NAMESPACE];

        $directory = dirname($appPath . $path);

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        try {
            write_file($appPath . $path, $content);
        } catch (\Exception $e) {
            $this->showError($e);
            exit();
        }

        $path = str_replace($appPath, '', $path);

        CLI::write(CLI::color('  created: ', 'green') . $path);
    }

    public function copydir($origine, $destination)
    {
        $dossier = opendir($origine);
        if (file_exists($destination)) {
            return 0;
        }
        mkdir($destination, fileperms($origine));
        $total = 0;
        while ($fichier = readdir($dossier)) {
            $l = array('.', '..');
            if (!in_array($fichier, $l)) {
                if (is_dir($origine . "/" . $fichier)) {
                    $total += $this->copydir("$origine/$fichier", "$destination/$fichier");
                } else {
                    copy("$origine/$fichier", "$destination/$fichier");
                    $total++;
                }
            }
        }
        return $total;
    }
}
