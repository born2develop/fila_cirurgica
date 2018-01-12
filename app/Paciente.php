<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    // protected $connection = 'connection-name'; // Set another connection
    protected $table = 'vis_dados_paciente_aghu'; // Set table name (rather than "cids")

    protected $primaryKey = '';

    // protected $dateFormat = ''; // Set a date format

	public $timestamps = false; // Set off creat_at and update_at tables
}
