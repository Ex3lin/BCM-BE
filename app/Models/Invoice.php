<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Invoice extends Model
{ 
    use HasFactory;
    protected $fillable = ['id', 'name', 'description', 'cost', 'type', 'status', 'deadline', 'submitted'];

    public function tags(): BelongsToMany{
        return $this->belongsToMany(Tag::class,'invoice_tag','invoice_id','tag_id');
    } 
}