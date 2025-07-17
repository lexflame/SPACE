<?php namespace App\Models;

use CodeIgniter\Model;

/**
 * Модель задач.
 * Отвечает за доступ к таблице `tasks`.
 */
class TaskModel extends Model
{
    /**
     * Название таблицы.
     *
     * @var string
     */
    protected $table = 'tasks';

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
        'title',
        'description',
        'link',
        'tag',
        'coords',
        'files',
        'completed',
        'created_at',
        'updated_at'
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
