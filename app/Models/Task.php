<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    const TABLE = 'tasks';

    protected $table = self::TABLE; 

    protected $fillable = ['title', 'description', 'status', 'due_date'];

}
