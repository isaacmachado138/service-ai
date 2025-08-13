<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiLog extends Model
{
    protected $fillable = [
        'provider', 'model', 'prompt', 'response', 'execution_time'
    ];
}
