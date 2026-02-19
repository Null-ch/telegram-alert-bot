<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MessageReaction extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'account',
        'chat_id',
        'chat_title',
        'message_id',
        'employee_id',
        'reaction',
    ];

    protected $table = 'message_reactions';
    protected $guarded = false;

    /**
     * Глобальный scope для фильтрации по дате (MoonShine date_range filter)
     */
    protected static function booted(): void
    {
        static::addGlobalScope('dateRangeFilter', function (Builder $query) {
            $request = request();
            if (! $request) {
                return;
            }
            $filters = $request->get('filters', []);

            if (! isset($filters['date_range']) || ! is_array($filters['date_range'])) {
                return;
            }

            $dateFrom = $filters['date_range']['from'] ?? $filters['date_range'][0] ?? null;
            $dateTo = $filters['date_range']['to'] ?? $filters['date_range'][1] ?? null;

            if ($dateFrom) {
                $query->where('created_at', '>=', Carbon::parse($dateFrom)->startOfDay());
            }
            if ($dateTo) {
                $query->where('created_at', '<=', Carbon::parse($dateTo)->endOfDay());
            }
        });
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
