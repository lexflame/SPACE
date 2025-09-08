<?php namespace App\Models;

use CodeIgniter\Model;

/**
 * Модель задач.
 * Отвечает за доступ к таблице `маркеров`.
 */
class MarkerModel extends Model
{
    /**
     * Название таблицы.
     *
     * @var string
     */
    protected $table = 'union_marker';

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
        'item_date',
        'sync_id',
        'obj',
        'remember'
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
