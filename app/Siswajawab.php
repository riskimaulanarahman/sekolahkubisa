<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswajawab extends Model
{
    use HasFactory;

    protected $table = 'tbl_siswajawab';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

}
