<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceTags extends Model
{ 
    use HasFactory;
    protected $table = 'invoice_tag';
    public $timestamps = false;
    protected $fillable = ['tag_id', 'invoice_id'];
}
