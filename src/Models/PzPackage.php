<?php

namespace Monsterz\Paagez\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PzPackage extends Model
{
    use HasFactory;

    protected $table = 'package';

    protected $guarded = [];

    public function __construct()
    {
        $this->table = config('paagez.db_prefix').$this->table;
    }
}
