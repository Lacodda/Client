<?php

    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class Document
        extends Model
    {
        public $fillable = ['client_id', 'invoice_number', 'invoice_date', 'act_number', 'act_date', 'amount', 'vat', 'final'];
    }
