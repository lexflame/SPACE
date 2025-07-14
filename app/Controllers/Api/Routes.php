<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\RouteModel;
use phpGPX\phpGPX;

class Routes extends ResourceController
{
    protected $modelName = 'App\Models\RouteModel';
    protected $format    = 'json';

    protected function getUserId()
    {
        return auth()->id();
    }

    public function index()
    {
        return $this->respond($this->model->where('user_id', $this->getUserId())->findAll());
    }

    public function show($id = null)
    {
        $route = $this->model->find($id);
        if (!$route || $route['user_id'] !== $this->getUserId()) {
            return $this->failNotFound('Маршрут не найден');
        }
        return $this->respond($route);
    }

    public function create()
    {
        $data = $this->request->getJSON(true) ?? $this->request->getPost();
        if (!$data || empty($data['name']) || empty($data['coordinates'])) {
            return $this->failValidationErrors('Имя и координаты обязательны');
        }
        $data['user_id'] = $this->getUserId();
        $this->model->insert($data);
        return $this->respondCreated($data);
    }

    public function update($id = null)
    {
        $route = $this->model->find($id);
        if (!$route || $route['user_id'] !== $this->getUserId()) {
            return $this->fail('Маршрут не найден или нет доступа');
        }
        $data = $this->request->getJSON(true);
        $this->model->update($id, [
            'name' => $data['name'] ?? $route['name'],
            'coordinates' => $data['coordinates'] ?? $route['coordinates']
        ]);
        return $this->respond($this->model->find($id));
    }

    public function delete($id = null)
    {
        $route = $this->model->find($id);
        if (!$route || $route['user_id'] !== $this->getUserId()) {
            return $this->fail('Маршрут не найден или нет доступа');
        }
        $this->model->delete($id);
        return $this->respondDeleted(['id' => $id]);
    }

    public function upload()
    {
        $file = $this->request->getFile('file');
        if (!$file->isValid()) return $this->fail('Файл не загружен');
        if (strtolower($file->getExtension()) !== 'gpx') return $this->failValidationErrors('Только GPX!');
        require_once(APPPATH . '../vendor/autoload.php'); // для phpGPX
        $gpx = new phpGPX();
        $gpxFile = $gpx->load($file->getTempName());
        $track = $gpxFile->tracks[0] ?? null;
        if (!$track || empty($track->segments)) return $this->fail('В GPX нет трека');

        $coords = [];
        foreach ($track->segments[0]->points as $pt) {
            $coords[] = [floatval($pt->longitude), floatval($pt->latitude)];
        }
        if (count($coords) < 2) return $this->fail('В маршруте слишком мало точек');
        $geojson = [
            'type' => 'LineString',
            'coordinates' => $coords
        ];

        $name = $this->request->getPost('name') ?: ($track->name ?: $file->getName());
        $this->model->insert([
            'user_id' => $this->getUserId(),
            'name' => $name,
            'coordinates' => $geojson,
        ]);
        return $this->respondCreated(['status' => 'ok']);
    }
}
