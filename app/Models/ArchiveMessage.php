<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArchiveMessage extends Model
{
    use HasFactory, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'text',
        'chat',
        'chat_id',
        'channel_type',
        'client_id',
        'message_id',
    ];

    protected $table = 'archive_messages';
    protected $guarded = false;

    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }
}