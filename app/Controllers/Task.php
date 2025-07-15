<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TaskModel;
use App\Models\LabelModel;
use App\Models\SubtaskModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Files\File;

class Task extends BaseController
{
    use ResponseTrait;

    public function index()
    {


        $model = new TaskModel();

        $builder = $model->builder();

        if ($status = $this->request->getGet('status')) {
            $builder->where('status', $status);
        }

        if ($dueDate = $this->request->getGet('due_date')) {
            $builder->where('due_date', $dueDate);
        }

        if ($label = $this->request->getGet('label')) {
            $builder->like('label', $label);
        }

        if ($search = $this->request->getGet('search')) {
            $builder->groupStart()
                ->like('title', $search)
                ->orLike('description', $search)
                ->groupEnd();
        }


        $query = $builder->get();
        $tasks = $query->getResult();



        $labelModel = new LabelModel();
        $subtaskModel = new SubtaskModel();
        
        foreach ($tasks as &$task) {
            $task->labels = $labelModel->where('task_id', $task->id)->findAll();
            $task->subtasks = $subtaskModel->where('task_id', $task->id)->findAll();
        }


        return $this->respond($tasks);
    }

    public function create()
    {
        $model = new TaskModel();
        $labelModel = new LabelModel();
        $subtaskModel = new SubtaskModel();

        $data = $this->request->getPost();
        $labels = $this->request->getPost('labels');
        $subtasks = $this->request->getPost('subtasks');

        unset($data['labels'], $data['subtasks']);

        // Обработка файла
        $file = $this->request->getFile('attachment');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $file->move(WRITEPATH . 'uploads');
            $data['attachment'] = $file->getName();
        }

        // Обработка координат карты (широта и долгота)
        $data['latitude'] = $this->request->getPost('latitude');
        $data['longitude'] = $this->request->getPost('longitude');

        if ($taskId = $model->insert($data, true)) {
            if (is_array($labels)) {
                foreach ($labels as $label) {
                    $labelModel->insert(['task_id' => $taskId, 'name' => $label]);
                }
            }
            if (is_array($subtasks)) {
                foreach ($subtasks as $subtask) {
                    $subtaskModel->insert(['task_id' => $taskId, 'title' => $subtask]);
                }
            }
            return $this->respondCreated(['message' => 'Задача создана']);
        }

        return $this->failValidationErrors($model->errors());
    }

    public function update($id)
    {
        $model = new TaskModel();
        $labelModel = new LabelModel();
        $subtaskModel = new SubtaskModel();

        $data = $this->request->getRawInput();
        $labels = $data['labels'] ?? [];
        $subtasks = $data['subtasks'] ?? [];
        unset($data['labels'], $data['subtasks']);

        // Обновление координат карты
        $data['latitude'] = $data['latitude'] ?? null;
        $data['longitude'] = $data['longitude'] ?? null;

        if ($model->update($id, $data)) {
            $labelModel->where('task_id', $id)->delete();
            foreach ($labels as $label) {
                $labelModel->insert(['task_id' => $id, 'name' => $label]);
            }

            $subtaskModel->where('task_id', $id)->delete();
            foreach ($subtasks as $subtask) {
                $subtaskModel->insert(['task_id' => $id, 'title' => $subtask]);
            }

            return $this->respond(['message' => 'Задача обновлена']);
        }

        return $this->failValidationErrors($model->errors());
    }

    public function delete($id)
    {
        $model = new TaskModel();
        $labelModel = new LabelModel();
        $subtaskModel = new SubtaskModel();

        if ($model->delete($id)) {
            $labelModel->where('task_id', $id)->delete();
            $subtaskModel->where('task_id', $id)->delete();
            return $this->respondDeleted(['message' => 'Задача удалена']);
        }

        return $this->failNotFound('Задача не найдена');
    }
}
