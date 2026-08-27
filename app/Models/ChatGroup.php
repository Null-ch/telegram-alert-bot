<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ChatGroup extends Model
{
    use HasFactory, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'account',
        'title',
    ];

    protected $table = 'chat_groups';
    protected $guarded = false;

    public function groupChats(): BelongsToMany
    {
        return $this->belongsToMany(GroupChat::class, 'chat_group_group_chat')->withTimestamps();
    }

    public function getChatTitlesAttribute(): string
    {
        return $this->groupChats->pluck('title')->implode(', ');
    }
}
