<?php
use Illuminate\Database\Eloquent\Model;

class Note extends Model {
    protected $guarded = [];

    public function users () {
        return $this->belongTo(User::class);
    }
}