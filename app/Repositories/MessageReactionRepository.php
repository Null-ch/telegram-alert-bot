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
        $query = MessageReaction::where('account', $account)
            ->where('chat_id', $chatId)
            ->where('message_id', $messageId)
            ->where('employee_id', $employeeId);

        $data = [
            'account' => $account,
            'chat_title' => $chatTitle,
            'reaction' => $reaction,
        ];

        if ($existing = $query->first()) {
            $existing->update($data);
        } else {
            $data = array_merge($data, [
                'account' => $account,
                'chat_id' => $chatId,
                'message_id' => $messageId,
                'employee_id' => $employeeId,
            ]);

            MessageReaction::create($data);
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
