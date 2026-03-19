<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    // TODO: add a array of identifiers so the previous record with same data gets deleted on new insert
    public array $duplicateIdentifier = [];

    protected $fillable = ['name', 'color', 'user_id'];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
