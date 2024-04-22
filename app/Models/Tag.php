<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{ 
    use HasFactory;
    protected $table = 'tags';
    protected $fillable = ['id', 'name'];

    public function invoiceTags(): BelongsToMany{
        return $this->belongsToMany(Invoice::class,'invoice_tag','tag_id','invoice_id');
    }
}
