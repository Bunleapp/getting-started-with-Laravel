<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

#[Fillable(['user_id', 'name', 'description', 'location'])]
class MeetupGroup extends Model
{
    public function user(): BelongsTo //return type
    {
        return $this->belongsTo(User::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);

    }
}
