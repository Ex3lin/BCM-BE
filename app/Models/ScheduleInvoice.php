<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ScheduleInvoice extends Model
{ 
    use HasFactory;
    protected $fillable = ['deadline','type','loop','duration','name','cost'];


}