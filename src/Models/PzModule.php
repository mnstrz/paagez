<?php

namespace Monsterz\Paagez\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PzModule extends Model
{
    use HasFactory;

    protected $table = 'module';

    protected $guarded = [];

    public function __construct()
    {
        $this->table = config('paagez.db_prefix').$this->table;
    }
}
