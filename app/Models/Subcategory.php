<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subcategory extends Model
{
    protected $fillable = ['name'];

    public function flashcards(): HasMany
    {
        return $this->hasMany(Flashcard::class);
    }
}
