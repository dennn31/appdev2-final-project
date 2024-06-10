<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;

    protected $fillable = [
        'entry_id', 
        'mood', 
        'content', 
        'image'
    ];

    public function entry()
    {
        return $this->belongsTo(Entry::class, 'entry_id');
    }
}
