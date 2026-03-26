<?php

namespace App\Repositories;

use App\Models\MessageReaction;

class MessageReactionRepository
{
    public function upsert(
        int|string $chatId,
        ?string $chatTitle,
        int $messageId,
        ?int $employeeId,
        string $account,
        ?string $reaction
    ): void {
        $record = MessageReaction::withTrashed()
            ->where('account', $account)
            ->where('chat_id', $chatId)
            ->where('message_id', $messageId)
            ->where('employee_id', $employeeId)
            ->first();

        if ($record) {
            if ($record->trashed()) {
                $record->restore();
            }

            $record->update([
                'chat_title' => $chatTitle,
                'reaction' => $reaction,
            ]);
        } else {
            MessageReaction::create([
                'account' => $account,
                'chat_id' => $chatId,
                'chat_title' => $chatTitle,
                'message_id' => $messageId,
                'employee_id' => $employeeId,
                'reaction' => $reaction,
            ]);
        }
    }

    public function delete(int|string $chatId, int $messageId, string $account): void
    {
        MessageReaction::where('account', $account)
            ->where('chat_id', $chatId)
            ->where('message_id', $messageId)
            ->delete();
    }
}
