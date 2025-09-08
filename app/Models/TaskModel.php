<?php namespace App\Models;

use CodeIgniter\Model;

/**
 * Модель задач.
 * Отвечает за доступ к таблице `task`.
 */
class TaskModel extends Model
{
    /**
     * Название таблицы.
     *
     * @var string
     */
    protected $table = 'union_task';

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
