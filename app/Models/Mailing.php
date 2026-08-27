<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mailing extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_QUEUED = 'queued';
    public const STATUS_COMPLETED = 'completed';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'message',
        'account',
        'status',
        'sent_chats',
        'failed_chats',
    ];

    protected $table = 'mailings';
    protected $guarded = false;

    /**
     * Новая запись создаётся перед постановкой в очередь, поэтому по умолчанию — 'queued'.
     * Существующие строки получают значение из миграции ('completed').
     */
    protected $attributes = [
        'status' => self::STATUS_QUEUED,
    ];

    protected $casts = [
        'sent_chats' => 'array',
        'failed_chats' => 'array',
    ];

    public function isQueued(): bool
    {
        return $this->status === self::STATUS_QUEUED;
    }

    public function hasResults(): bool
    {
        return ! is_null($this->sent_chats) || ! is_null($this->failed_chats);
    }

    public function getHasErrorsAttribute(): bool
    {
        return count($this->failed_chats ?? []) > 0;
    }

    public function getChatsLabelAttribute(): string
    {
        if ($this->isQueued()) {
            return 'В очереди';
        }

        if (! $this->hasResults()) {
            return '—';
        }

        $sentCount = count($this->sent_chats ?? []);
        $failedCount = count($this->failed_chats ?? []);
        $total = $sentCount + $failedCount;

        return $failedCount > 0 ? "{$sentCount}/{$total}" : (string) $sentCount;
    }

    public function getErrorsLabelAttribute(): string
    {
        if ($this->isQueued()) {
            return 'В очереди';
        }

        if (! $this->hasResults()) {
            return '—';
        }

        $failedCount = count($this->failed_chats ?? []);

        return $failedCount > 0 ? "Ошибок: {$failedCount}" : 'Без ошибок';
    }

    public function getSentChatsHtmlAttribute(): string
    {
        if ($this->isQueued()) {
            return '<span class="text-secondary">Рассылка ещё выполняется…</span>';
        }

        return $this->chatsToHtml($this->sent_chats ?? [], 'Нет отправленных чатов');
    }

    public function getFailedChatsHtmlAttribute(): string
    {
        if ($this->isQueued()) {
            return '<span class="text-secondary">Рассылка ещё выполняется…</span>';
        }

        $chats = $this->failed_chats ?? [];

        if (empty($chats)) {
            return '<span class="text-secondary">Ошибок не было</span>';
        }

        $items = collect($chats)->map(function (array $chat): string {
            $title = e($chat['title'] ?? $chat['chat_id'] ?? '—');
            $error = e($chat['error'] ?? 'Неизвестная ошибка');

            return "<li><strong>{$title}</strong> — <span class=\"text-red-600\">{$error}</span></li>";
        })->implode('');

        return "<ul class=\"m-0 pl-4\">{$items}</ul>";
    }

    /**
     * @param  array<int, array{title?: string, chat_id?: string}>  $chats
     */
    private function chatsToHtml(array $chats, string $emptyMessage): string
    {
        if (empty($chats)) {
            return "<span class=\"text-secondary\">{$emptyMessage}</span>";
        }

        $items = collect($chats)->map(function (array $chat): string {
            $title = e($chat['title'] ?? $chat['chat_id'] ?? '—');

            return "<li>{$title}</li>";
        })->implode('');

        return "<ul class=\"m-0 pl-4\">{$items}</ul>";
    }
}
