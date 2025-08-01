<?php namespace App\Controllers;

use App\Models\TacmapMapModel;
use App\Models\FileModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Images\ImageManager;

class Tacmap extends BaseController
{
    public function __construct()
    {
        $this->db = db_connect();
    }
    public function index($id = 1)
    {   
        $model = new TacmapMapModel();
        $image = \Config\Services::image();
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
            $arrImg[$key]['img_src']        = getimagesize($url_src);
            $arrImg[$key]['img_src']['url'] = $url_src;
            
            $pathToThumb = explode('/', $item['full_path']);
            $name = 'thumb_'.array_pop($pathToThumb);
            
            $pathToSrc = implode('/', $pathToThumb).'/'.$item['name'];
            
            $arrImg[$key]['img_src']['pathToThumb'] = implode('/', $pathToThumb).'/'.$name;

            $explThumb = explode('/',$arrImg[$key]['img_src']['pathToThumb']);
            
            foreach ($explThumb as $key_cat => $value_cat) {
                if($value_cat === 'data'){unset($explThumb[$key_cat]);}
                if($value_cat === 'app'){unset($explThumb[$key_cat]);}
                if($value_cat === 'public'){unset($explThumb[$key_cat]);}
            }

            if(!file_exists($arrImg[$key]['img_src']['pathToThumb'])){
                $arrImg[$key]['img_src']['resThumb'] = boolval(
                    $image->withFile($pathToSrc)
                    ->resize(718.16, 718.91, true, 'box')
                    ->save($arrImg[$key]['img_src']['pathToThumb'])
                );
            }else{
                $arrImg[$key]['img_src']['resThumb'] = true;
            }

            $arrImg[$key]['img_src']['pathToThumb'] = str_replace(' ','%20','http://'.$_SERVER['HTTP_HOST'].implode('/', $explThumb));
            $arrMap['width_flex_box'] = intval($arrImg[$key]['img_src'][0])/12;
            $arrMap['height_flex_box'] = intval($arrImg[$key]['img_src'][1])/12;
            $arrMap['widthMapWrapper'] = ($arrMap['width_flex_box'] * $arrMap['flex_count']);
            $arrMap['heightMapWrapper'] = ($arrMap['height_flex_box'] * $arrMap['flex_count']);
            $arrMap['posTopMapWrapper'] = $arrMap['widthMapWrapper']/2;
            $arrMap['posLeftMapWrapper'] = $arrMap['heightMapWrapper']/2;
            $arrMap['startMapPosTop'] = 0;
            $arrMap['startMapPosLeft'] = $arrMap['height_flex_box'];
        }
        $arrMap['src']          = $arrImg;
        $arrMap['dif']          = getimagesize('http://'.$_SERVER['HTTP_HOST'].$arrMap['path']);
        $arrMap['dif']['url']   = 'http://'.$_SERVER['HTTP_HOST'].$arrMap['path'];
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
