<?php
/**
 * 这个命令, 是通过快捷的方式, 快速生成所需要开发的主题的目录结构和必要的文件
 */
namespace App\Console\Commands;

use Illuminate\Console\Command;

class SmartBroScaffold extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smartbro:scaffold {theme}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Use this command to create the whole theme files structure quickly';

    private $rootFolderPath;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->rootFolderPath = resource_path('views/frontend/custom/');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $themeName = $this->argument('theme');

        if(empty($themeName)){
            echo 'No theme name provide!'.PHP_EOL;
            die('Please use "php artisan smartbro:scaffold your_theme"'.PHP_EOL);
        }

        // Make sure the give theme folder is not existed
        if(file_exists($this->rootFolderPath.$themeName)){
            echo 'The theme "'.$themeName.'" is existed!'.PHP_EOL;
        }else{
            $structure = $this->_getScaffoldingStructure($themeName);
            $systemError = false;

            foreach ($structure as $item) {
                if(strpos($item,DIRECTORY_SEPARATOR) !== false && strpos($item,'.') === false){
                    // it's a folder
                    if(file_exists($this->rootFolderPath.$item)){
                        // 已经存在
                        continue;
                    }else{
                        if(mkdir($this->rootFolderPath.$item)){
                            echo 'The folder "'.$this->rootFolderPath.$item.' is created!'.PHP_EOL;
                        }else{
                            echo 'Creating folder "'.$this->rootFolderPath.$item.' failed!'.PHP_EOL;
                            $systemError = true;
                            break;
                        }
                    }
                }else{
                    // it's a file name
                    $newFile = fopen($this->rootFolderPath.$item,"w") or die('System error! Please try again'.PHP_EOL);
                    fwrite($newFile,'// Create something awesome'.PHP_EOL);
                    fclose($newFile);
                    echo 'The file "'.$this->rootFolderPath.$item.' is created!'.PHP_EOL;
                }
            }

            if($systemError){
                echo 'System error! Please try again'.PHP_EOL;
            }else{
                echo PHP_EOL;
                echo '*******************************************'.PHP_EOL;
                echo 'All Good! Keep going for something awesome.'.PHP_EOL;
                echo '*******************************************'.PHP_EOL;
            }
        }
    }

    /**
     * 返回主题基本结构数组
     * @param $themeName
     * @return array
     */
    private function _getScaffoldingStructure($themeName){
        return [
            $themeName.DIRECTORY_SEPARATOR,
            $themeName.DIRECTORY_SEPARATOR.'layouts',
            $themeName.DIRECTORY_SEPARATOR.'layouts/frontend',
            $themeName.DIRECTORY_SEPARATOR.'pages',
            $themeName.DIRECTORY_SEPARATOR.'templates',
            $themeName.DIRECTORY_SEPARATOR.'catalog',
            $themeName.DIRECTORY_SEPARATOR.'checkout',
            $themeName.DIRECTORY_SEPARATOR.'customers',
            $themeName.DIRECTORY_SEPARATOR.'order',
            'app'.DIRECTORY_SEPARATOR,
            'app'.DIRECTORY_SEPARATOR.'Controllers',
            'app'.DIRECTORY_SEPARATOR.'Models',
            'app'.DIRECTORY_SEPARATOR.'migrations',
            'app'.DIRECTORY_SEPARATOR.'routes',
            '_custom.js',
            'webpack.mix.js',
            '_custom.scss',
            'README.md',
        ];
    }
}
