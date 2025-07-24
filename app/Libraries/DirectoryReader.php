<?php

namespace App\Libraries;

class DirectoryReader
{
    protected $result = [];

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
    protected function readDirectory(string $path, bool $includeFiles, string $relative = null): void
    {
        ini_set('memory_limit','-1');

        if (!is_dir($path)) {
            return;
        }

        $items = preg_grep('/^([^.])/', scandir($path));
        $finSumm = 0;
        foreach ($items as $item) {
            $finSumm++;
            if($finSumm > 10){
                break;
            }else{

                if (in_array($item, ['.', '..'])) continue;

                $fullPath = $path . DIRECTORY_SEPARATOR . $item;
                $relativePath = $relative ? $relative . DIRECTORY_SEPARATOR . $item : $item;

                if (is_dir($fullPath)) {
                    $this->result[] = [
                        'type' => 'dir',
                        'name' => $item,
                        'path' => $relativePath,
                        'full_path' => $fullPath,
                    ];
                    $this->readDirectory($fullPath, $includeFiles, $relativePath);
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
                                            $arrTag[] = preg_replace('/\s+/', '', $string);
                                        }
                                    }
                                }
                                if($string === 'Gallery'){
                                    $writeFlagPath = true;
                                }
                            }
                        }
                    }

                    $this->result[] = [
                        'type' => 'file',
                        'name' => $item,
                        'path' => $relativePath,
                        'full_path' => $fullPath,
                        'node' => fileinode($fullPath),
                        'size' => filesize($fullPath),
                        'modified' => filemtime($fullPath),
                        'hash_summ' => md5(file_get_contents($fullPath)).'_'.md5(filesize($fullPath)).'_'.md5($fullPath),
                        'arrTag' => $arrTag,
                    ];
                }


            }
        }
    }
}
