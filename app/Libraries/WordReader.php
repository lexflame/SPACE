<?php

namespace App\Libraries;

class WordReader
{
    protected $path;
    
    public $xml_style;
    public $xml_body;

    public $html_style;
    public $html_body;

    public function __construct(string $path)
    {
        if (!file_exists($path)) {
            throw new InvalidArgumentException("Файл не найден: $path");
        }
        if (!is_file($path)) {
            throw new InvalidArgumentException("Указанный путь не является файлом: $path");
        }
        $this->path = $path;
    }

    public function getXmlBody()
    {
        return $this->xml_body;
    }

    public function getHtmlBody()
    {
        return $this->html_body;
    }

    public function getHtmlStyle()
    {
        return $this->html_style;
    }

    // Основной метод: вернуть текст из документа
    public function getText(): string
    {
        $ext = strtolower(pathinfo($this->path, PATHINFO_EXTENSION));

        switch ($ext) {
            case 'docx':
                return $this->extractTextFromDocx();
            case 'docm':
                return $this->extractTextFromDocx();
            case 'dotx':
                return $this->extractTextFromDocx();
            case 'dotm':
                return $this->extractTextFromDocx();
            case 'doc':
                // Старый DOC: используем внешнюю утилиту
                return $this->extractTextFromDoc();
            default:
                throw new \RuntimeException("Не поддерживаемый формат файла: .$ext");
        }
    }

    function xml_attribute($object, $attribute)
    {
        if(isset($object[$attribute])){
            $string_attr = (string) $object[$attribute];
            if($string_attr === '-'){
                $str = 'take';
            }else{
                $str = $string_attr;
            }
            return $str;
        }
    }

    // Извлечение из DOCX без внешних зависимостей
    protected function extractTextFromDocx(): string
    {
        // DOCX — это ZIP-архив с XML внутри
        $zip = new \ZipArchive();
        if ($zip->open($this->path) !== true) {
            throw new \RuntimeException("Не удалось открыть DOCX как ZIP: " . $this->path);
        }

        $texts = [];

        // Обычно основной текст находится в word/document.xml
        $docXmlIndex = $zip->locateName('word/document.xml');
        if ($docXmlIndex === false) {
            // Альтернативно ищем любой document.xml
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $name = $zip->getNameIndex($i);
                if (stripos($name, 'word/document.xml') !== false) {
                    $docXmlIndex = $zip->getNameIndex($i);
                    break;
                }
            }
        }

        // Получение стилей
        $result = file_get_contents('zip://' . $this->path . '#word/styles.xml');
        $this->loadStyle(simplexml_load_string($result,null, 0, 'w', true));

        // Бинарное получение текста документа
        $result = file_get_contents('zip://' . $this->path . '#word/document.xml');
        $xml = simplexml_load_string($result,null, 0, 'w', true);
        $this->xml_body = $xml->body;

        $inc_class = [];
        $nameFile = explode('.',explode('/', $this->path)[4])[0];
        

        $this->html_body .= '<div class="article">';
        $this->html_body .= '<h3>'.$nameFile.'</h3>';

        foreach($this->xml_body[0] as $key => $value){
            $style_class = $this->xml_attribute($value->pPr->pStyle,'val');
            $this->html_body .= "<p class='".$style_class."'>";
            if($key == "p"){
                foreach ($value->r as $kkey => $vvalue) {
                    $text = str_replace('_', '', (string)$vvalue->t);
                    $this->html_body .= $text;
                    if(isset($vvalue->footnoteReference)){
                        $note = $this->xml_attribute($vvalue->footnoteReference,'id');
                        if(strlen($note) > 0){
                            $this->html_body .= '<sup class="noteref">['.$note.']</sup>';
                        }
                        
                    }
                }
            }
            $this->html_body .= "</p>";
            $inc_class[$style_class] = 1;
        }

        // Получение сносок
        $result = file_get_contents('zip://' . $this->path . '#word/footnotes.xml');
        $xml = simplexml_load_string($result,null, 0, 'w', true);
        $footnotes = $xml->footnote;

        
        $arrNote = [];
        foreach($footnotes as $key => $value){
            if($key == "footnote"){
                $num = 0;
                foreach($value->p as $pkey => $pvalue){
                    $strNote = '';
                    foreach($pvalue->r as $rkey => $rvalue){
                        $text = (string)$rvalue->t;
                        if(strlen($text) > 0){
                            $strNote .= $text; 
                        }
                    }
                    if(strlen($strNote) > 5){
                        $arrNote[] = $strNote;
                    }
                }
            }
        }
        $num = 1;
        if(count($arrNote) > 0){
            $this->html_body .= '<div class="label_note">Сноски</div>';
            $this->html_body .= '<div class="note_box">';
            foreach($arrNote as $key => $text){
                if(strpos($text, 'YandexDisk') != false){
                    $origin_text = $text;
                    $text = str_replace('\\','/',$text);
                    $text = str_replace('//','/',$text);
                    $text = str_replace('D:/YandexDisk/','https://disk.yandex.ru/client/disk/',$text);
                    $text = '<a id="ref_'.$num.'" target="_blank" href="'.$text.'">'.$origin_text.'</a>';
                }else{
                    $text = '<a id="ref_'.$num.'" target="_blank" href="https://yandex.ru/search/?text='.$text.'">'.$text.'<a>';
                }
                $this->html_body .= '<p><sup class="noteref">['.$num.']</sup>  '.$text.'</p>';
                $num++;
            }
            $this->html_body .= '</div>';
        }
        $this->html_body .= '</div>';
        
        $this->html_style = '<style>';
        foreach($inc_class as $name => $val){
            if(isset($this->xml_style[$name])){
                $this->html_style .= '.'.$name.' {';
                $data_class = $this->xml_style[$name];
                if(isset($data_class['rPr']['b'])){
                    $this->html_style .= 'font-weight: 700;';
                }
                if(isset($data_class['rPr']['rFonts']['@attributes']['cs'])){
                    $this->html_style .= 'font-family: '.$data_class['rPr']['rFonts']['@attributes']['cs'].';';
                }
                $this->html_style .= '} ';
            }
        }
        $this->html_style .= '.article {font-family: Tahoma;width: 100%;overflow: auto;word-wrap: break-word;position:relative;}';
        $this->html_style .= '.article h3 {border-bottom: 1px solid black;padding-bottom:15px;}';
        $this->html_style .= '.article .note_box {padding: 5px;margin-top: 34px;border: 1px solid black;}';
        $this->html_style .= '.article .label_note {width: min-content;padding: 5px;border: 1px solid black;position: absolute;background: white;}';
        $this->html_style .= '.article .noteref {color: cornflowerblue;font-weight: 700;}';
        $this->html_style .= '.article p {line-height: 25px;}';
        $this->html_style .= '</style>';
        

        if ($docXmlIndex === false) {
            $zip->close();
            throw new \RuntimeException("Не найден файл word/document.xml внутри DOCX.");
        }

        $xmlContent = $zip->getFromIndex($docXmlIndex);
        $zip->close();

        // Простая очистка XML вPlain text: удаляем теги, конвертим сущности
        // Можно улучшить, удалив стоп-слова и конвертировав спецсимволы.
        $text = $this->stripXmlTags($xmlContent);

        // Дополнительно: DOCX может содержать перенасыщение переносами строк
        // Приводим к нормальному виду
        $text = preg_replace('/\r\n?/', PHP_EOL, $text);
        return $this->cleanText($text);
    }

    public function loadStyle( $xml ): bool
    {
        $xml = json_decode(json_encode($xml),true);
        foreach($xml as $key=>$val)
        {
            if(is_array($val)){
                foreach($val as $key => $item)
                {
                    if(is_array($item)){
                        foreach($item as $name => $val)
                        {
                            if(isset($val['styleId'])){
                                $class_name = $val['styleId'];
                                if($class_name === '-'){$class_name = 'take';}
                                $this->xml_style[$class_name] = $item;
                            }
                        }
                    }else{
                        $this->xml_style[$key] = $item;
                    }
                }
            }else{
                $this->xml_style[$key] = $val;
            }
        }

        return true;
    }

    // Извлечение из DOC с помощью внешней утилиты (antiword)
    protected function extractTextFromDoc(): string
    {
        // Убедитесь, что утилита antiword доступна в PATH
        $cmd = escapeshellcmd(sprintf('antiword %s', escapeshellarg($this->path)));

        // В некоторых системах можно использовать cat или soffice для конвертации
        // Здесь минимальная реализация через antiword
        $output = [];
        $returnVar = 0;
        exec($cmd, $output, $returnVar);

        if ($returnVar !== 0) {
            // Попробуем альтернативу с soffice в режиме конвертации
            $sofficeCmd = escapeshellcmd(sprintf('soffice --headless --convert-to txt:Text "%s" --outdir "%s"', escapeshellarg($this->path), sys_get_temp_dir()));
            exec($sofficeCmd, $output, $returnVar);
            if ($returnVar === 0) {
                // Поиск созданного txt-файла в той же директории
                $base = pathinfo($this->path, PATHINFO_FILENAME);
                $txtPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $base . ".txt";
                if (file_exists($txtPath)) {
                    return $this->readAndReturn($txtPath);
                }
            }
            throw new \RuntimeException("extractTextFromDoc: Не удалось извлечь текст из DOC: внешний инструмент не сработал.");
        }

        // Объединяем вывод antiword
        $text = implode(PHP_EOL, $output);
        return $this->cleanText($text);
    }

    // Вспомогательный метод: чистка текста от XML-тегов
    protected function stripXmlTags(string $xml): string
    {
        // Простейшее извлечение текста: удаляем теги и сохраняем содержимое между ними
        // Но сохраняем пробелы и переносы строк
        $text = strip_tags($xml);
        // Разделяем при помощи удаления XML сущностей
        $text = html_entity_decode($text, ENT_QUOTES | ENT_XML1, 'UTF-8');
        // Удаляем лишние пробелы
        $text = preg_replace('/[ \t]+/', ' ', $text);
        return trim($text);
    }

    // Финальная очистка текста: нормализация переносов и лишних пробелов
    protected function cleanText(string $text): string
    {
        // Удаляем пустые строки
        $lines = preg_split('/\r?\n/', $text);
        $clean = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line !== '') {
                $clean[] = $line;
            }
        }
        return implode(PHP_EOL, $clean);
    }

    // Доп. утилита для чтения файла в случае конвертации
    protected function readAndReturn(string $path): string
    {
        $contents = @file_get_contents($path);
        if ($contents === false) {
            throw new \RuntimeException("Не удалось прочитать файл: $path");
        }
        return $this->cleanText($contents);
    }
}