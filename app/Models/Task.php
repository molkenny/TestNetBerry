<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'usuario', 
        'task', 
        'status', 
    ];

    public function categories()
    {
        return $this->hasMany(TaskCategory::class,'id_task')
            ->leftJoin('categories', 'task_categories.id_category', '=', 'categories.id')
            ->select('task_categories.*','categories.name');
    }
}
