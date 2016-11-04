<?php

    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class Client
        extends Model
    {
        public $fillable = ['alias', 'name', 'inn', 'kpp', 'address'];
    }
