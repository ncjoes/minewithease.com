<?php

namespace App\Interfaces;

interface Payable
{
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo;
    public function amount(): string;
    public function localAmount(): string;
    public function getUrlAttribute(): string;
    public function getAdminUrlAttribute(): string;
}
