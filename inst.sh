#!/bin/bash

mkdir -p MyTodoistApp/{app/{Controllers,Models,Views,Database/Migrations},public,writable/uploads}
cd MyTodoistApp

echo "Создаю структуру файлов и копирую содержимое..."

# Controllers
cat > app/Controllers/Task.php <<'EOF'
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
EOF

# Models
cat > app/Models/TaskModel.php <<'EOF'
<?php
namespace App\Models;

use CodeIgniter\Model;

class TaskModel extends Model
{
    protected $table = 'tasks';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'title',
        'description',
        'status',
        'due_date',
        'attachment',
        'latitude',
        'longitude',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'title' => 'required|min_length[3]',
    ];

    protected $validationMessages = [
        'title' => [
            'required' => 'Название обязательно',
            'min_length' => 'Минимум 3 символа в названии',
        ],
    ];

    protected $skipValidation = false;
}

EOF

cat > app/Models/LabelModel.php <<'EOF'
<?php
namespace App\Models;

use CodeIgniter\Model;

class LabelModel extends Model
{
    protected $table = 'labels';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'task_id',
        'name',
    ];

    protected $useTimestamps = false;
}

EOF

cat > app/Models/SubtaskModel.php <<'EOF'
<?php
namespace App\Models;

use CodeIgniter\Model;

class SubtaskModel extends Model
{
    protected $table = 'subtasks';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'task_id',
        'title',
        'is_done',
    ];

    protected $useTimestamps = false;

    protected $validationRules = [
        'title' => 'required|min_length[1]',
    ];
}

EOF

# Views
cat > app/Views/task_form.php <<'EOF'
<div class="container mt-4 text-light bg-dark p-4 rounded">
  <form id="taskForm" enctype="multipart/form-data">
    <div class="form-group">
      <label for="title">Название задачи</label>
      <input type="text" class="form-control" id="title" name="title" required>
    </div>

    <div class="form-group">
      <label for="description">Описание</label>
      <textarea class="form-control" id="description" name="description"></textarea>
    </div>

    <div class="form-group">
      <label for="due_date">Дата завершения</label>
      <input type="date" class="form-control" id="due_date" name="due_date">
    </div>

    <div class="form-group">
      <label for="labels">Метки (через запятую)</label>
      <input type="text" class="form-control" id="labels" name="labels">
    </div>

    <div class="form-group">
      <label for="subtasks">Подзадачи (по одной на строку)</label>
      <textarea class="form-control" id="subtasks" name="subtasks"></textarea>
    </div>

    <div class="form-group">
      <label for="attachment">Прикрепить файл</label>
      <input type="file" class="form-control-file" id="attachment" name="attachment">
    </div>

    <div class="form-group">
      <label>Координаты</label>
      <div class="d-flex gap-2">
        <input type="text" class="form-control" id="latitude" name="latitude" placeholder="Широта">
        <input type="text" class="form-control" id="longitude" name="longitude" placeholder="Долгота">
      </div>
    </div>

    <button type="submit" class="btn btn-primary mt-3">Сохранить задачу</button>
  </form>
</div>

<script>
  $('#taskForm').on('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    formData.set('labels', $('#labels').val().split(',').map(l => l.trim()));
    formData.set('subtasks', $('#subtasks').val().split('\n').map(s => s.trim()));

    $.ajax({
      url: '/api/task/create',
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
        alert('Задача сохранена');
        location.reload();
      },
      error: function(err) {
        alert('Ошибка при сохранении задачи');
        console.error(err);
      }
    });
  });
</script>
EOF

# Migration
cat > app/Database/Migrations/2025-07-15-CreateTasksTable.php <<'EOF'
<?php
// Миграция для таблицы задач
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTasksTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'title'       => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status'      => [
                'type'    => 'VARCHAR',
                'constraint' => '50',
                'default' => 'pending',
            ],
            'due_date'    => [
                'type' => 'DATE',
                'null' => true,
            ],
            'attachment'  => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'latitude'    => [
                'type' => 'DECIMAL',
                'constraint' => '10,8',
                'null' => true,
            ],
            'longitude'   => [
                'type' => 'DECIMAL',
                'constraint' => '11,8',
                'null' => true,
            ],
            'created_at'  => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at'  => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('tasks');
    }

    public function down()
    {
        $this->forge->dropTable('tasks');
    }
}

EOF

cd ..
zip -r MyTodoistApp.zip MyTodoistApp

echo "Готово. Файл: MyTodoistApp.zip"