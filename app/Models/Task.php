<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';
    protected $fillable = ['title', 'description', 'created_by', 'status'];

    public function creator(){
        return $this->belongsTo(User::class, 'created_by');
    }
}
