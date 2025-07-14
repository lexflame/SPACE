<?php

namespace App\Models;

use CodeIgniter\Model;

class RouteModel extends Model
{
    protected $table      = 'routes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'name', 'coordinates'];
    protected $useTimestamps = true;
    protected $returnType = 'array';
    protected $casts = [
        'coordinates' => 'array',
    ];
}
