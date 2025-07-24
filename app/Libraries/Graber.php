<?php
namespace App\Libraries;

use Config\Services;

class Graber
{
    protected $interact;
    protected $workDir;

    public function __construct()
    {
        // В CI4 библиотеку лучше подключать через сервис или вручную
        $this->interact = Services::interact(); // если вы зарегистрировали в сервисах
        // либо создавайте через new \App\Libraries\Interact();

        $this->workDir = $_SERVER['WORKDIR'] ?? WRITEPATH; // лучше использовать WRITEPATH или задавать в конфиге

        // Отключаем ошибки, как в оригинале (при необходимости)
        error_reporting(0);
        ini_set('display_errors', '0');
    }

    public function GetPage(string $link_page): array
    {
        $this->interact->InterInit($link_page);
        $page = $this->interact->GetContentPage();

        $doc = new \DOMDocument();

        // Чтобы избежать предупреждений от некорректного HTML
        libxml_use_internal_errors(true);
        $doc->loadHTML($page);
        libxml_clear_errors();

        $title_tag = $doc->getElementsByTagName('h1');
        $array_div = $doc->getElementsByTagName('div');
        $cat = $this->ulListCat($page);

        $Name_file = '';
        foreach ($title_tag as $elm) {
            if ($elm->getAttribute('class') === 'page-header__title') {
                $Name_file = trim($elm->nodeValue) . '.html';
                break;
            }
        }

        return [
            'page' => $doc,
            'title' => $Name_file,
            'cat' => $cat,
            'divs_ul' => $array_div,
        ];
    }

    public function GetDataProject(?string $link): array
    {
        if (empty($link)) {
            return [];
        }
        $parts = explode('/', $link);

        $res = [];
        foreach ($parts as $part) {
            if (in_array($part, ['http:', 'https:', ''])) {
                continue;
            }
            $res[] = $part;
        }
        return $res;
    }

    public function ArrToDir(array $array): string
    {
        $dir = implode('/', $array) . '/';
        return rtrim($this->workDir, '/') . '/load/' . $dir;
    }

    public function TextStringParserNNMClub(string $path, string $search_set): ?string
    {
        if (!file_exists($path)) {
            return null;
        }
        $page = file_get_contents($path);
        $page = iconv("CP1251", "UTF-8", $page);

        $arrStringPage = explode(">", $page);

        foreach ($arrStringPage as $key => $stElm) {
            if (stripos($stElm, $search_set) !== false) {
                $res = $arrStringPage[$key + 1] ?? '';
                break;
            }
        }

        $resParts = explode(',', $res ?? '');
        $res = $resParts[0] ?? '';
        $res = explode(' ', $res);

        $res = array_filter($res, fn($v) => !empty($v));
        $res = array_shift($res);

        return $res ?: null;
    }

    public function TextDOMParserNNMClub(string $path, string $search_set): ?string
    {
        if (!file_exists($path)) {
            return null;
        }
        $page = file_get_contents($path);
        libxml_use_internal_errors(true);
        $doc = new \DOMDocument();
        $doc->loadHTML($page);
        libxml_clear_errors();

        $spans = $doc->getElementsByTagName('span');

        foreach ($spans as $index => $span) {
            if (stripos($span->textContent, $search_set) !== false) {
                $nextSpan = $spans->item($index + 1);
                return $nextSpan ? $nextSpan->textContent : null;
            }
        }

        return null;
    }

    public function AddProjectDir(array $arrProject): ?string
    {
        $dir = rtrim($this->workDir, '/') . '/load/' . ($arrProject[0] ?? '');

        if (!is_dir($dir)) {
            if (!mkdir($dir, 0777, true) && !is_dir($dir)) {
                return null;
            }
        }

        return $dir;
    }

    public function AddLimb(array $arr): bool
    {
        $dirProgress = '';
        $countKey = count($arr);
        $key = 0;

        foreach ($arr as $value) {
            $value = urldecode($value);
            $dirProgress .= '/' . $value;
            $fullPath = rtrim($this->workDir, '/') . '/load' . $dirProgress;

            if (!is_dir($fullPath)) {
                if (mkdir($fullPath, 0777, true) === true) {
                    $key++;
                }
            } else {
                $key++;
            }
        }

        return $key === $countKey;
    }

    public function ulListCat(string $html): string
    {
        libxml_use_internal_errors(true);
        $doc = new \DOMDocument();
        $doc->loadHTML($html);
        libxml_clear_errors();

        $array_div = $doc->getElementsByTagName('div');

        $cat = 'Основной раздел';

        foreach ($array_div as $div) {
            if ($div->getAttribute('class') === 'page-header__categories-links') {
                $aTags = $div->getElementsByTagName('a');
                foreach ($aTags as $a) {
                    $cat = trim($a->nodeValue ?: $a->textContent);
                    break;
                }
            }
            if ($div->getAttribute('class') === 'noarticletext') {
                $cat = 'Не надено или удаленно';
                break;
            }
        }

        return $cat;
    }

    public function aListPage(array $array_link, string $origin_source, string $project): array
    {
        $main = [];

        foreach ($array_link as $item) {
            $href = urldecode($item->getAttribute('href'));

            if (isset($main[$href])) {
                continue;
            }

            if (!$this->array_strpos($href) && str_contains($href, $project)) {
                $linkArr = explode('/', $href);
                $linkArr = array_pop($linkArr);

                if (!empty($linkArr) && strlen($linkArr) > 1) {
                    $main[$linkArr] = $origin_source . $href;
                }
            }
        }

        return $main;
    }

    public function ulListPage($divs_ul, string $origin_source): array
    {
        $main = [];

        foreach ($divs_ul as $ul) {
            $ulIns = $ul->getElementsByTagName('ul');

            foreach ($ulIns as $ulFor) {
                $liList = $ulFor->getElementsByTagName('li');

                foreach ($liList as $li) {
                    $aTags = $li->getElementsByTagName('a');

                    foreach ($aTags as $a) {
                        $href = urldecode($a->getAttribute('href'));

                        if (in_array($href, $main, true)) {
                            continue;
                        }

                        if (!$this->array_strpos($href)) {
                            $linkArr = explode('/', $href);
                            $linkArr = array_pop($linkArr);

                            if (!empty($linkArr) && strlen($linkArr) > 1) {
                                $main[$linkArr] = $origin_source . $href;
                            }
                        }
                    }
                }
            }
        }

        return $main;
    }

    public function array_strpos(string $haystack, array $needles = null): bool
    {
        $needles = $needles ?? [
            'Участник',
            'community.',
            'Служебная',
            'Шаблон',
            'Тема',
            'Стена_обсуждения',
            'Обсуждение_шаблона',
            'Форум:',
            'Сообщество',
            'Обсуждение:',
            'wikia',
            'Правила',
            'Администраторы',
            'Local_Sitemap',
            'action'
        ];

        foreach ($needles as $needle) {
            if (strpos($haystack, $needle) !== false) {
                return true;
            }
        }

        return false;
    }

    public function ApiPool($value = '')
    {
        // Пока пусто, реализуйте по необходимости
    }
}
