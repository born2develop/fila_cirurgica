<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cid extends Model
{
	// protected $connection = 'connection-name'; // Set another connection
    protected $table = 'vpc_cid_aghu'; // Set table name (rather than "cids")

    protected $primaryKey = '';

    // protected $dateFormat = ''; // Set a date format

	public $timestamps = false; // Set off creat_at and update_at tables

	public $fillable = ['dsc_cid','cod_cid'];
}
