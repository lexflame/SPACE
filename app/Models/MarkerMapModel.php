<?php namespace App\Models;

use CodeIgniter\Model;

/**
 * Модель задач.
 * Отвечает за доступ к таблице `union_marker_map`.
 */
class MarkerMapModel extends Model
{
    /**
     * Название таблицы.
     *
     * @var string
     */
    protected $table = 'union_marker_map';

    /**
     * Первичный ключ таблицы.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Поля, разрешённые для массового присвоения.
     *
     * @var array
     */
    protected $allowedFields = [
        'map_id',
        'marker_id ',
    ];

    /**
     * Автоматическое управление временем создания/обновления.
     *
     * @var bool
     */
    protected $useTimestamps = true;

    /**
     * Формат возвращаемых данных.
     *
     * @var string
     */
    protected $returnType = 'array';
}
