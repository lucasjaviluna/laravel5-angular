<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Joke extends Model
{
    protected $table = 'jokes';
    protected $fillable = ['body', 'user_id'];

    public function user() {
      return $this->belongsTo('App\User', 'user_id');
    }
}
