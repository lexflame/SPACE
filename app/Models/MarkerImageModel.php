<?php

namespace App\Models;

use CodeIgniter\Model;

class MarkerImageModel extends Model
{
    protected $table = 'marker_images';
    protected $allowedFields = ['marker_id', 'image_path'];

    public function getImages($marker_id)
    {
        return $this->where('marker_id', $marker_id)->findAll();
    }
}
