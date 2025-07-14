<?php

namespace App\Controllers;

use App\Models\MapModel;
use App\Models\MarkerModel;
use App\Models\MarkerImageModel;
use App\Models\MarkerHistoryModel;
use CodeIgniter\HTTP\Files\UploadedFile;

class Mapeditor extends BaseController
{
    public function index($map_id = 1)
    {
        $mapModel = new MapModel();
        $data = [
            'maps' => $mapModel->getAll(),
            'categories' => $mapModel->getCategories(),
            'layers' => $mapModel->getLayers($map_id),
            'selected_map_id' => $map_id
        ];
        foreach ($data['maps'] as $elm) {
            if(intval($elm['id']) === intval($map_id)){
                $data['current']['path_back'] = $elm['image_path'];
                break;
            }
        }
        
        // echo '<pre>'; print_r($data); echo '</pre>'; exit;
        return view('mapeditor/index', $data);
    }

    public function getMarkers($map_id)
    {
        $markerModel = new MarkerModel();
        return $this->response->setJSON($markerModel->getMarkers($map_id));
    }

    public function getMarker($id)
    {
        $markerModel = new MarkerModel();
        return $this->response->setJSON($markerModel->getMarker($id));
    }

    public function saveMarker()
    {
        $request = $this->request;
        $id = $request->getPost('id');
        $markerData = [
            'map_id' => $request->getPost('map_id'),
            'layer_id' => $request->getPost('layer_id'),
            'category_id' => $request->getPost('category_id'),
            'title' => $request->getPost('title'),
            'description' => $request->getPost('description'),
            'icon' => $request->getPost('icon'),
            'icon_color' => $request->getPost('icon_color'),
            'icon_size' => $request->getPost('icon_size'),
            'x' => $request->getPost('x'),
            'y' => $request->getPost('y')
        ];
        $markerModel = new MarkerModel();
        $historyModel = new MarkerHistoryModel();
        $imageModel = new MarkerImageModel();
        $user_id = session()->get('user_id') ?? null;
        $action = $id ? 'edit' : 'create';

        $before = $id ? $markerModel->getMarker($id) : null;
        if ($id) {
            $markerModel->update($id, $markerData);
        } else {
            $markerModel->save($markerData);
            $id = $markerModel->getInsertID();
        }
        $after = $markerModel->getMarker($id);
        $historyModel->save([
            'marker_id' => $id,
            'user_id' => $user_id,
            'action' => $action,
            'changes' => json_encode(['before'=>$before,'after'=>$after]),
            'created_at' => date('Y-m-d H:i:s')
        ]);
        // Загрузка новых картинок
        $files = $this->request->getFiles();
        if (isset($files['images'])) {
            foreach ($files['images'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(ROOTPATH.'public/uploads/', $newName);
                    $imageModel->save([
                        'marker_id'=>$id,
                        'image_path'=>'/uploads/'.$newName
                    ]);
                }
            }
        }
        return $this->response->setJSON(['success'=>1,'id'=>$id]);
    }

    public function getMarkerImages($marker_id)
    {
        $imageModel = new MarkerImageModel();
        return $this->response->setJSON($imageModel->getImages($marker_id));
    }

    public function moveMarker()
    {
        $data = $this->request->getJSON(true);
        $markerModel = new MarkerModel();
        $historyModel = new MarkerHistoryModel();
        $id = $data['id'];
        $before = $markerModel->getMarker($id);
        $markerModel->update($id, ['x'=>$data['x'], 'y'=>$data['y']]);
        $after = $markerModel->getMarker($id);
        $historyModel->save([
            'marker_id'=>$id,
            'user_id'=>session()->get('user_id') ?? null,
            'action'=>'move',
            'changes'=>json_encode(['before'=>$before,'after'=>$after]),
            'created_at'=>date('Y-m-d H:i:s')
        ]);
        return $this->response->setJSON(['success'=>1]);
    }

    public function getHistory($marker_id)
    {
        $historyModel = new MarkerHistoryModel();
        return $this->response->setJSON($historyModel->getHistory($marker_id));
    }

    public function setLayerVisibility()
    {
        $data = $this->request->getJSON(true);
        db_connect()->table('map_layers')->where('id', $data['layer_id'])->update(['visible'=>$data['visible']]);
        return $this->response->setJSON(['success'=>1]);
    }
}
