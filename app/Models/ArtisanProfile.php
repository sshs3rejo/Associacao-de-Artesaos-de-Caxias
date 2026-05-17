<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArtisanProfile extends Model
{
    protected $fillable = [
        'user_id', 'cpf', 'phone', 'specialty', 'bio', 'profile_photo',
        'instagram', 'facebook', 'whatsapp', 'is_public', 'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
            'approved_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isApproved(): bool
    {
        return $this->approved_at !== null;
    }
}
