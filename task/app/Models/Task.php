<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at']; // for soft deletes

    const TYPE_WORK = 0;
    const TYPE_PERSONAL = 1;
    const TYPE_URGENT = 2;

    const Types = [
        self::TYPE_WORK => ["id" => self::TYPE_WORK, "name" => 'Work'],
        self::TYPE_PERSONAL => ["id" => self::TYPE_PERSONAL, "name" => 'Personal'],
        self::TYPE_URGENT => ["id" => self::TYPE_URGENT, "name" => 'Urgent'],
    ];

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'type',
        'is_done',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getTask($userId, $pagination = []){

        return Task::where('user_id',$userId)
            ->withoutTrashed();

    }

    public static function mapTask($task){
        return [
            'id' => $task->id,
            'user' => $task->user_id,
            'title' => $task->title,
            'description' => $task->description,
            'type' => [
                'id' => $task->type,
                'name' => Task::Types[$task->type]['name'] ?? 'Unknown',
            ],
            'deleted_at' => $task->deleted_at,
            'created_at' => $task->created_at,
            'updated_at' => $task->updated_at,
        ];
    }
}