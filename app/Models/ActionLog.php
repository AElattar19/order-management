<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionLog extends Model
{
    use HasFactory;
    protected $table = 'action_logs';
    public $timestamps = true;
    protected $fillable = ['user_id', 'action_type','order_id'];

}
