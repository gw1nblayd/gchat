<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends MainModel
{
    public static function scopeMy(Builder $query): Builder
    {
        return $query->whereHas(
            'users',
            fn(Builder $q): Builder => $q
                ->where('user_id', auth()->id()),
        );
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'chat_user');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function isMy(): bool {
        return $this->users()->where('user_id', auth()->id())->exists();
    }
}
