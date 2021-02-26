<?php

require 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager;


Manager::schema()->dropIfExists('users');
Manager::schema()->create('users', function ($table) {
    $table->increments('id');
    $table->string('email')->unique();
    $table->string('password');
    $table->timestamps();
});

Manager::schema()->dropIfExists('notes');
Manager::schema()->create('notes', function ($table) {
    $table->increments('id');
    $table->integer('user_id');
    $table->string('note');
    $table->timestamps();
});