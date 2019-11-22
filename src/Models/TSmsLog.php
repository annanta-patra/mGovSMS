<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TSmsLog extends Model{

    protected $table = 't_sms_logs';
    protected $primaryKey = 'intSmsLogId';
    public $timestamps = false;


}