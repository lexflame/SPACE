<?php

namespace App\Libraries;

use App\Models\FileModel;

class DirectoryReader
{
    protected $result = [];
    protected $debug = false;
    public    $error = [];
    public    $error_tem = [];

    /**
     * Получить структуру директорий и файлов
     *
     * @param string $basePath Путь до корневой директории
     * @param bool $includeFiles Включать ли файлы в результат
     * @return array
     */
    public function getStructure(string $basePath, bool $includeFiles = true): array
    {
        $this->result = [];
        $this->readDirectory($basePath, $includeFiles);
        
        if($this->debug === true){
            foreach($this->error as $key => $item){
                var_dump($item);
                print_r($this->error_tem[$key]);
            }
            exit;
        }

        return $this->result;
    }

    /**
     * Рекурсивный обход директории
     *
     * @param string $path
     * @param bool $includeFiles
     * @param string|null $relative
     * @return void
     */
    protected function readDirectory(string $path, bool $includeFiles, string $relative = null, $ret = false): void
    {
        ini_set('memory_limit','-1');

        if (!is_dir($path)) {
            return;
        }

        if($ret === true){
            echo $path."\r\n";
        }

        $items = preg_grep('/^([^.])/', scandir($path));
        
        foreach ($items as $key => $item) {

                if($this->debug === true){
                    if(intval($key) > 3) break;
                }

                if (in_array($item, ['.', '..'])) continue;

                $fullPath = $path . DIRECTORY_SEPARATOR . $item;
                $relativePath = $relative ? $relative . DIRECTORY_SEPARATOR . $item : $item;

                if (is_dir($fullPath)) {

                    

                    $elm = [
                        'name' => $item,
                        'path' => $relativePath,
                        'type' => 'dir',
                        'node' => fileinode($fullPath),
                        'full_path' => $fullPath,
                        'hash_summ' => md5($fullPath),
                    ];

                    if($this->debug === true) $this->result[] = $elm;

                    $this->loadDB($elm);
                    $this->readDirectory($fullPath, $includeFiles, $relativePath, true);
                } elseif ($includeFiles) {
                    
                    $explodePath = explode('/', $fullPath);
                    
                    $arrTag = [];
                    $tagName = '';

                    if(is_array($explodePath)){
                        if(count($explodePath) > 0){
                            $lenExpPath = count($explodePath) - 1;
                            $writeFlagPath = false;
                            foreach($explodePath as $key => $string){
                                if($writeFlagPath === true){
                                    if(strlen($string) > 1){
                                        if(intval($key) === intval($lenExpPath)){
                                            preg_match('#\((.*?)\)#', $string, $match);
                                            $tagName = (isset($match[1]))?$match[1]:false;
                                            $explodeName = explode(',',$tagName);
                                            if(is_array($explodeName)){
                                                if(count($explodeName) > 0){
                                                    foreach($explodeName as $key => $stringFName){
                                                        if(strlen($stringFName) > 0){
                                                            $arrTag[] = preg_replace('/\s+/', '', $stringFName);
                                                        }
                                                    }
                                                }
                                            }
                                        }else{
                                            $arrTag[] = [
                                                'string' => preg_replace('/\s+/', '', $string),
                                                'hash' => md5($string)
                                            ];
                                        }
                                    }
                                }
                                if($string === 'Gallery'){
                                    $writeFlagPath = true;
                                }
                            }
                        }
                    }

                    $elm = [
                        'type' => 'file',
                        'name' => $item,
                        'path' => $relativePath,
                        'full_path' => $fullPath,
                        'node' => fileinode($fullPath),
                        'size' => filesize($fullPath),
                        'modified' => filemtime($fullPath),
                        'hash_summ' => md5(file_get_contents($fullPath)).'_'.md5(filesize($fullPath)).'_'.md5($fullPath),
                        'json_tag' => json_encode($arrTag),
                    ];

                    if($this->debug === true) $this->result[] = $elm;

                    $this->loadDB($elm);
                }
        }
    }
    function loadDB( $elm ): bool
    {
            
        $model = new FileModel();
        $isSave = count($model->where('hash_summ', $elm['hash_summ'])->findAll()) > 0;
        
        if($isSave){
            $elm['id'] = intval($model->where('hash_summ', $elm['hash_summ'])->findAll()[0]['id']);
            $method = 'update';
        }else{
            $method = 'save';
        }

        $resUp = false;
        try {
            $resUp = $model->save($elm,false);
        } catch (\Exception $e) {
            $this->error[] = $e->getMessage();
            $elm['method'] = $method;
            $this->error_tem[] = $elm;
        }

        return $resUp;

    }
}
