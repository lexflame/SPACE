<?php namespace App\Controllers;

use App\Models\TacmapMapModel;
use App\Models\FileModel;
use CodeIgniter\RESTful\ResourceController;

class Tacmap extends BaseController
{
    public function __construct()
    {
        $this->db = db_connect();
    }
    public function index($id = 1)
    {   
        $model = new TacmapMapModel();
        $modelFile = new FileModel();
        $arrMap = $model->where('id', $id)->findAll()[0];
        
        $builder = $this->db->table("file");
        $builder->select('*');
        $builder->like('full_path','tacmap_'.$arrMap['name'],'match');
        $builder->having('type', 'file');;
        $query = $builder->get();
        $arrImg = [];
        foreach ($query->getResult() as $key => $value) {
            $arrImg[$key+1] = (array) $value; 
        }

        foreach ($arrImg as $key => $item) {
            $url_src = 'http://'.$_SERVER['HTTP_HOST'].'/yandexdisk/Gallery/'.str_replace(' ', '%20', $item['path']);
            $arrImg[$key]['img_src'] = getimagesize($url_src);
            $arrImg[$key]['img_src']['url'] = $url_src;
        }
        $arrMap['src'] = $arrImg;
        $arrMap['dif'] = getimagesize('http://'.$_SERVER['HTTP_HOST'].$arrMap['path']);
        // echo '<pre>'; print_r($arrMap); echo '</pre>'; exit;
        return view('tacmap/index', $arrMap);
    }
    public function dev($id = 1)
    {   
        echo '<title>Песочница</title>';
        $model = new TacmapMapModel();
        $arrMap = $model->where('id', $id)->findAll()[0];
        $arrMap['dif'] = getimagesize('http://'.$_SERVER['HTTP_HOST'].$arrMap['path']);
        // echo '<pre>'; print_r($arrMap); echo '</pre>'; exit;
        return view('dev/index', $arrMap);
    }

    public function data()
    {
        $model = new TacmapModel();
        return $this->response->setJSON($model->getMarkers());
    }
}
