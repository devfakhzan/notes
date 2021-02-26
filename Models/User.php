<?php
use Illuminate\Database\Eloquent\Model;

class User extends Model {
    protected $guarded = [];

    public function notes () {
        return $this->hasMany(Note::class);
    }
}