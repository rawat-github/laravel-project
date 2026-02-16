<?php

namespace App\Models;


use ValueResearch\Scaffold\Models\BaseModel;

class Task extends BaseModel
{
    // TODO: add a array of identifiers so the previous record with same data gets deleted on new insert
    public array $duplicateIdentifier = [];

    // TODO: add columns here which can be added/modified by API
    protected $fillable = [];

    //
}
