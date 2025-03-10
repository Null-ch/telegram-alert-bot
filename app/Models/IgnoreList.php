<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IgnoreList extends Model
{
    use HasFactory, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tg_id',
    ];

    protected $table = 'ignore_lists';
    protected $guarded = false;

    public function scopeNotDeleted($query)
    {
        return $query->whereNull('deleted_at');
    }
}
