<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DataModel;
use App\Libraries\Interact;

class Worker extends BaseController
{
    protected $dataModel;
    protected $interact;

    public function __construct()
    {
        $this->dataModel = new DataModel();
        $this->interact = new Interact();
    }

    public function index()
    {
        // Entry point, intentionally left blank.
    }

    public function Inter()
    {
        $argv = $_SERVER['argv'] ?? [];
        $link = $argv[3] ?? null;

        if (!empty($link) && strlen($link) > 10) {
            $this->interact->InteractMonolith($link, false);
        } else {
            echo "Dont Set arg to pars\r\n";
        }
    }

    public function ScandirDuplicate()
    {
        $dir = $_SERVER['WORKDIR'] . '/load/wiki.snk-games.net/Основной раздел/';
        $dirData = array_diff(scandir($dir), ['.', '..']);

        foreach ($dirData as $item) {
            $dataSelect = $this->dataModel->SelectLikeCondition('scheduler', 'url', str_ireplace('.html', '', $item));
            if ($dataSelect) {
                $this->dataModel->DeleteDB('scheduler', $dataSelect[0]['id']);
            }
        }
    }

    public function S1()
    {
        echo "Scheduler\r\n";
        $prj = $_SERVER['argv'][3] ?? '';

        $this->dataModel = new DataModel();
        $this->interact = new Interact();

        $this->dataModel->orderBy('id', 'RANDOM');
        $arrScheduler = $this->dataModel->SelectFrom('scheduler', [
            'where' => ['status' => 1],
            'like'  => ['url' => $prj],
        ], 100);

        foreach ($arrScheduler as $item) {
            $this->interact->InteractMonolith($item['url']);
            $item['status'] = 2;
            $this->dataModel->ReplaceInto('scheduler', $item);
        }
    }

    public function S2()
    {
        echo "SchedulerSection\r\n";
        $this->interact = new Interact();
        $projectData = [];
        $projectData['ArrName'] = $this->interact->ScandirLoad();
        $DIR = $_SERVER['WORKDIR'] . '/load/';

        foreach ($projectData['ArrName'] as $item) {
            $res = $this->interact->ScandirPage($DIR . $item);
            foreach ($res as $key => $elm) {
                if (stristr($elm, 'html')) {
                    $projectData['HtmlFile'][$item][$key] = [
                        'Name'  => $elm,
                        'Path'  => $DIR . $item . '/' . $elm,
                        'URL'   => './load/' . $item . '/' . $elm,
                    ];
                }
            }
        }

        $this->dataModel = new DataModel();

        foreach ($projectData['HtmlFile'] as $files) {
            foreach ($files as $elm) {
                $pageArc = file_get_contents($elm['Path']);
                if (strlen($pageArc) > 10) {
                    $category = $this->graber->ulListCat($pageArc);
                    $prjPath = explode('/', $elm['Path']);
                    $prjFilename = array_pop($prjPath);
                    $prjName = array_pop($prjPath);
                    $mkdir = $DIR . $prjName . '/' . $category;
                    if (!is_dir($mkdir)) {
                        mkdir($mkdir, 0777, true);
                    }
                    rename($elm['Path'], $mkdir . '/' . $prjFilename);

                    $repl = [
                        'link'    => urldecode($elm['Name']),
                        'section' => $category,
                        'project' => $prjName
                    ];

                    $this->dataModel->ReplaceInto('link_list', $repl);
                }
            }
        }
    }

    public function S3()
    {
        echo "S3 Scheduler clear\n";
        $scheduler = $this->dataModel->SelectFrom('scheduler');

        foreach ($scheduler as $item) {
            if (!str_contains($item['url'], '.html')) {
                $this->dataModel->DeleteDB('scheduler', $item['id']);
            }
        }
    }

    public function ListLink()
    {
        $res = $this->dataModel->SelectFrom('link_list');
        foreach ($res as $item) {
            echo $item['link'] . "\n";
        }
    }

    public function RewHref()
    {
        echo "Rewrited Href\n";
        $arrScheduler = $this->dataModel->SelectFrom('scheduler', ['where' => ['status' => 2]]);
        foreach ($arrScheduler as $item) {
            $res = $this->interact->ReplaceHref($item['url']);
            if ($res) {
                $item['status'] = 3;
                $this->dataModel->ReplaceInto('scheduler', $item);
            }
        }
    }

    public function JazzSection()
    {
        echo "Jazz Section\n";
        $this->interact->RunJazzSection();
    }

    public function Google()
    {
        echo "Google Pagespeed\n";
        $arrScheduler = $this->dataModel->SelectFrom('scheduler', ['where' => ['status' => 3]]);
        foreach ($arrScheduler as $item) {
            $res = $this->interact->GoogleSpeed($item['url']);
            if ($res) {
                $item['status'] = 4;
                $this->dataModel->ReplaceInto('scheduler', $item);
            }
        }
    }

    public function BasikAuth()
    {
        echo "Auth File\n";
        $this->interact->WriteHtaccessAuth();
    }

    public function GetContentPage()
    {
        echo "Get content for page\n";
        $arrScheduler = $this->dataModel->SelectFrom('scheduler', ['where' => ['status' => 4]]);
        foreach ($arrScheduler as $item) {
            $html = $this->interact->GetContent($item['url']);
            if ($html && strlen($html) > 100) {
                $item['status'] = 5;
                $item['content'] = $html;
                $this->dataModel->ReplaceInto('scheduler', $item);
            }
        }
    }

    public function PageGrad()
    {
        echo "Page Gradient Scan\n";
        $this->interact->PageGradScan();
    }
}
