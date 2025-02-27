<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Client;
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

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function scopeDateRange($query, $dateStart, $dateEnd = null)
    {
        $startDate = Carbon::parse($dateStart)->startOfDay();
        if ($dateEnd === null) {
            $endDate = Carbon::parse($dateStart)->endOfDay();
        } else {
            $endDate = Carbon::parse($dateEnd)->endOfDay();
        }

        return $query->whereBetween('created_at', [
            $startDate,
            $endDate
        ]);
    }
}
