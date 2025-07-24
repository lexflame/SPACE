<?php

namespace App\Models;

use CodeIgniter\Model;

class FileModel extends Model
{
    protected $table = 'file';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'name',
        'path',
        'full_path',
        'node',
        'size',
        'modified',
        'hash_summ',
        'date_set',
    ];

    protected $useTimestamps = true;

    protected $validationRules = [
        'name'       => 'required|max_length[255]',
        'path'       => 'required|max_length[255]',
        'full_path'  => 'required|max_length[255]',
        'node'       => 'required|integer|max_length[200]',
        'size'       => 'permit_empty|integer|max_length[200]',
        'modified'   => 'permit_empty|valid_date',
        'hash_summ'  => 'permit_empty|max_length[255]',
        'date_set'   => 'required|valid_date',
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
}
