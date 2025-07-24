<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DataModel;
use App\Libraries\Interact;

class Inter extends BaseController
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
        $request = service('request');
        $page = $request->getGet('page');
        $ajax = $request->getGet('ajax');

        if ($ajax) {
            switch ($ajax) {
                case 'wgetmonit':
                    return $this->interact->WgetMonit();
            }
        }

        echo view('header');

        switch ($page) {
            case 'example':
                break;
            case 'listpage':
                return $this->ListPage();
            case 'getpage':
                return $this->GetPage();
            case 'InteractPage':
                return $this->InteractPage();
            case 'getlist':
                return $this->GetList();
            case 'scheduler':
                return $this->Scheduler();
            case 'edit':
                return $this->Edit();
            case 'settings':
                return $this->Settings();
            case 'game':
                return $this->Game();
            case 'project':
                return $this->Project();
            default:
                return $this->Default();
        }

        echo view('footer');
    }

    public function Default()
    {
        $request = service('request');
        $delete = $request->getGet('delete');
        $id = $request->getGet('id');
        $table = $request->getGet('table');
        $type = $request->getGet('type');
        $enterStr = $request->getGet('enter_str');
        $argHlink = $request->getGet('arg_hlink');
        $example = $request->getGet('example');

        if ($delete) {
            $deletePath = $_SERVER['WORKDIR'] . $delete;
            if ($type === 'path') {
                @rmdir($deletePath);
            } else {
                @unlink($deletePath);
            }
            $this->dataModel->DeleteDB($table, $id);
            if (isset($_SERVER['HTTP_REFERER'])) {
                return redirect()->to($_SERVER['HTTP_REFERER']);
            }
        }

        if ($example) {
            $start = $this->dataModel->link();
            $this->dataModel->select($start);
            $this->dataModel->where($start . '.id', 23);
            $finish = $this->dataModel->result();
            echo '<pre>'; print_r($finish); echo '</pre>';
        }

        if ($enterStr) {
            return redirect()->to('/?page=getlist&action=search&str=' . $enterStr);
        }

        if ($argHlink) {
            return $this->RewriteLink($argHlink);
        }

        return '';
    }

    public function RewriteLink($link)
    {
        $linkParts = explode('/', $link);
        array_shift($linkParts);
        unset($linkParts[0]);
        $fileFind = array_pop($linkParts) . '.html';

        exec('find ' . $_SERVER['WORKDIR'] . ' -name ' . $fileFind, $searchOutput);

        if (!empty($searchOutput[0])) {
            $searchPath = explode('/', $searchOutput[0]);
            foreach ($searchPath as $key => $value) {
                if ($value != 'load') {
                    unset($searchPath[$key]);
                } else {
                    break;
                }
            }
            $redirectPath = implode('/', $searchPath);
            return redirect()->to($redirectPath);
        }

        return '';
    }

    public function ListPage()
    {
        $str = $this->dataModel->ArgGetValue('str');
        $this->interact->InteractMonolith($str, true);
        return '';
    }

    public function GetList()
    {
        $action = $this->dataModel->ArgGetValue('action');
        $str = $this->dataModel->ArgGetValue('str');

        $result = $this->interact->InteractMonolith($str);

        if ($action === 'search') {
            return view('search', ['str' => $str, 'result' => $result]);
        }

        return view('list', ['result' => $result]);
    }

    public function GetPage()
    {
        $str = $this->dataModel->ArgGetValue('str');
        $pageData = $this->interact->GetPage($str);
        return view('page', ['data' => $pageData]);
    }

    public function Scheduler()
    {
        $task = $this->dataModel->ArgGetValue('task');

        if ($task === 'sync') {
            $result = $this->interact->SyncScheduler();
            return view('OK', ['oparation' => 'SyncScheduler']);
        }

        return view('scheduler');
    }

    public function InteractPage()
    {
        $link = $this->dataModel->ArgGetValue('str');
        $res = $this->interact->InteractPage($link);
        if ($res === true) {
            return view('OK', ['oparation' => __FUNCTION__]);
        }
        return view('NO', ['oparation' => __FUNCTION__]);
    }

    public function Edit()
    {
        $request = service('request');
        $table = $this->dataModel->ArgGetValue('table');
        $id = $this->dataModel->ArgGetValue('id');
        $action = $this->dataModel->ArgGetValue('action');

        if ($action === 'save') {
            $dataRep = $request->getPost();
            $this->dataModel->ReplaceInto('game', $dataRep);
        }

        $editData = $this->dataModel->SelectFrom($table, ['id' => $id]);

        return view('edit', [
            'table' => $table,
            'id' => $id,
            'editData' => $editData[0] ?? [],
        ]);
    }

    public function Settings()
    {
        $action = $this->dataModel->ArgGetValue('action');

        if ($action === 'set_settings') {
            $post = service('request')->getPost();
            $name = key($post);
            $value = array_values($post)[0];

            $result = $this->dataModel->UpdateTable('Settings', 'NAME', $name, 'VALUE', $value);
            return view($result ? 'OK' : 'NO', ['oparation' => 'Установка настроек ' . $name]);
        }

        $settings = $this->dataModel->SelectAll('Settings');
        return view('settings', ['settings' => $settings]);
    }

    public function Game()
    {
        $games = $this->dataModel->SelectAll('game');
        return view('game', ['games' => $games]);
    }

    public function Project()
    {
        $projects = $this->dataModel->SelectAll('project');
        return view('project', ['projects' => $projects]);
    }
}
