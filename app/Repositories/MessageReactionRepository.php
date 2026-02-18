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
        ?string $reaction
    ): void {
        $query = MessageReaction::where('chat_id', $chatId)
            ->where('message_id', $messageId)
            ->where('employee_id', $employeeId);

        $data = [
            'chat_title' => $chatTitle,
            'reaction' => $reaction,
        ];

        if ($existing = $query->first()) {
            $existing->update($data);
        } else {
            $data = array_merge($data, [
                'chat_id' => $chatId,
                'message_id' => $messageId,
                'employee_id' => $employeeId,
            ]);

            MessageReaction::create($data);
        }
    }
}
