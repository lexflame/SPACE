<?php namespace App\Controllers;

use App\Models\TaskModel;
use CodeIgniter\API\ResponseTrait;

class TaskController extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        return view('tasks/index');
    }

    public function list()
    {
        $model = new TaskModel();
        return $this->respond($model->orderBy('id', 'DESC')->findAll());
    }

    public function create()
    {
        $model = new TaskModel();
        $data = $this->request->getJSON(true);

        $model->insert($data);
        return $this->respondCreated(['id' => $model->insertID()]);
    }

    public function update($id)
    {
        $model = new TaskModel();
        $data = $this->request->getJSON(true);

        $model->update($id, $data);
        return $this->respondUpdated();
    }

    public function delete($id)
    {
        $model = new TaskModel();
        $model->delete($id);
        return $this->respondDeleted();
    }
}
