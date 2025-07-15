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

