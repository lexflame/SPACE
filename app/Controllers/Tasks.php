<?php

namespace App\Controllers;
use App\Models\ProjectModel;
use App\Models\TaskModel;
use App\Models\AttachmentModel;

class Tasks extends BaseController
{
    public function index()
    {
        $projectM = new ProjectModel();
        $projects = $projectM->findAll();
        return view('panel').view('tasks', ['projects'=>$projects]);
    }

    // Список задач с фильтрами
    public function list()
    {
        $model = new TaskModel();
        $p = $this->request->getGet();
        $builder = $model->orderBy('is_done asc, due_date asc, id desc');
        if (!empty($p['project_id'])) $builder->where('project_id', $p['project_id']);
        if (!empty($p['tag']))        $builder->like('tags', $p['tag']);
        if (!empty($p['is_done']))    $builder->where('is_done', (int)$p['is_done']);
        return $this->response->setJSON($builder->findAll());
    }

    public function add()
    {
        $model = new TaskModel();
        $data = $this->request->getPost([
            'project_id','title','description','due_date','repeat_rule',
            'cost','is_done','tags','map','map_marker'
        ]);
        $data['is_done'] = 0;
        $id = $model->insert($data, true);
        // вложения
        if($files = $this->request->getFiles()){
            $attModel = new AttachmentModel();
            foreach($files['files']??[] as $file){
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(WRITEPATH.'uploads', $newName);
                    $attModel->insert([
                        'task_id'=>$id,
                        'filename'=>$file->getClientName(),
                        'url'=> base_url('writable/uploads/'.$newName)
                    ]);
                }
            }
        }
        return $this->response->setJSON(['success'=>true]);
    }

    public function update($id)
    {
        $model = new TaskModel();
        $data = $this->request->getPost([
            'title','description','due_date','repeat_rule','cost','tags','map','map_marker'
        ]);
        $model->update($id, $data);
        // вложения
        if($files = $this->request->getFiles()){
            $attModel = new AttachmentModel();
            foreach($files['files']??[] as $file){
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(WRITEPATH.'uploads', $newName);
                    $attModel->insert([
                        'task_id'=>$id,
                        'filename'=>$file->getClientName(),
                        'url'=> base_url('writable/uploads/'.$newName)
                    ]);
                }
            }
        }
        return $this->response->setJSON(['success'=>true]);
    }
    public function change_status($id)
    {
        $status = $this->request->getPost('status');
        $model  = new TaskModel();
        $model->update($id, ['status'=>$status]);
        return $this->response->setJSON(['success'=>true]);
    }
    public function delete($id)
    {
        (new TaskModel())->delete($id);
        return $this->response->setJSON(['success'=>true]);
    }

    public function toggle($id)
    {
        $model = new TaskModel();
        $task = $model->find($id);
        if($task) {
            $model->update($id, ['is_done'=>!$task['is_done']]);
            return $this->response->setJSON(['success'=>true]);
        }
        return $this->response->setJSON(['success'=>false]);
    }

    // Для drag-and-drop (смена порядка, проекта)
    public function reorder()
    {
        // Тебе нужно будет добавить поле "sort_order" в задачи для хранения порядка.
        // Тут реализовать по необходимости (пример оставить).
    }
}
