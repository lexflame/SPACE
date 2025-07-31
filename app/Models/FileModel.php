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
        'type',
        'full_path',
        'node',
        'size',
        'modified',
        'hash_summ',
        'date_set',
        'json_tag',
    ];

    protected $useTimestamps = true;

    // protected $validationRules = [
    //     'name'       => 'required|max_length[255]',
    //     'path'       => 'required|max_length[255]',
    //     'type'       => 'required|max_length[255]',
    //     'node'       => 'required|integer|max_length[200]',
    //     'full_path'  => 'required|max_length[255]',
    //     'size'       => 'permit_empty|integer|max_length[200]',
    //     'modified'   => 'permit_empty|valid_date',
    //     'hash_summ'  => 'permit_empty|max_length[255]',
    //     'date_set'   => 'required|valid_date',
    //     'json_tag'   => 'permit_empty|max_length[1255]',
    // ];

    // protected $validationMessages = [];
    protected $skipValidation = true;
}
