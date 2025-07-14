<?php namespace App\Models;
use CodeIgniter\Model;
class CategoryModel extends Model
{
    protected $table = 'marker_categories';
    protected $allowedFields = ['name','color','icon'];
}
