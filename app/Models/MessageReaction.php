<?php

namespace App\Models;

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
     * Игнорируем массив от DateRange фильтра MoonShine (from/to)
     */
    public function setCreatedAtAttribute($value): void
    {
        if (is_array($value)) {
            return;
        }
        $this->attributes['created_at'] = $value;
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
