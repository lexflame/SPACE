<?php

namespace App\Models;

use CodeIgniter\Model;

class MarkerHistoryModel extends Model
{
    protected $table = 'marker_history';

    protected $allowedFields = [
        'marker_id', 'user_id', 'action', 'changes', 'created_at'
    ];

    public function getHistory($marker_id)
    {
        return $this->where('marker_id', $marker_id)->orderBy('created_at', 'desc')->findAll();
    }
}
