<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = ['flashcard_id','title', 'option_number'];

    public function flashcard()
    {
        return $this->belongsTo(Flashcard::class);
    }
}
