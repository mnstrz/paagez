<?php

namespace Monsterz\Paagez\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PzConfig extends Model
{
    use HasFactory;

    protected $table = 'config';

    protected $guarded = [];

    public function __construct()
    {
        $this->table = config('paagez.db_prefix').$this->table;
    }
}
