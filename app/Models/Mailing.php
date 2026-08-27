<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mailing extends Model
{
    use HasFactory, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'message',
        'account',
        'sent_chats',
        'failed_chats',
    ];

    protected $table = 'mailings';
    protected $guarded = false;

    protected $casts = [
        'sent_chats' => 'array',
        'failed_chats' => 'array',
    ];

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
        if (! $this->hasResults()) {
            return '—';
        }

        $failedCount = count($this->failed_chats ?? []);

        return $failedCount > 0 ? "Ошибок: {$failedCount}" : 'Без ошибок';
    }

    public function getSentChatsHtmlAttribute(): string
    {
        return $this->chatsToHtml($this->sent_chats ?? [], 'Нет отправленных чатов');
    }

    public function getFailedChatsHtmlAttribute(): string
    {
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
