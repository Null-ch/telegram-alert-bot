<?php

namespace App\Services\Common;

use Throwable;
use App\DTO\MessageReactionDTO;
use App\DTO\EmployeeDTO;
use Illuminate\Support\Facades\Log;
use App\Repositories\MessageReactionRepository;

class MessageReactionService
{
    public MessageReactionRepository $messageReactionRepository;

    public function __construct(
        MessageReactionRepository $messageReactionRepository
    ) {
        $this->messageReactionRepository = $messageReactionRepository;
    }

    public function store(MessageReactionDTO $dto, string $account, ?EmployeeDTO $employee = null): void
    {
        try {
            $chatId = $dto->getChatId();
            $chatTitle = $dto->getChatTitle();
            $messageId = $dto->getMessageId();
            $employeeId = $employee?->getEmployeeId();

            if ($chatId === null || $messageId === null) {
                return;
            }

            $newReactions = $dto->getNewReactions();
            $hasOldReaction = $dto->hasOldReaction();

            $reactionValue = null;

            if (!empty($newReactions)) {
                // Если есть новые реакции - сохраняем их (может быть несколько)
                $reactionValue = implode(',', array_filter($newReactions));
            } elseif ($hasOldReaction) {
                // Если есть только старые реакции и новых нет - считаем, что реакция удалена
                $reactionValue = null;
            }

            $this->messageReactionRepository->upsert(
                $chatId,
                $chatTitle,
                $messageId,
                $employeeId,
                $account,
                $reactionValue
            );
        } catch (Throwable $e) {
            Log::error("Error when store message reaction: {$e->getMessage()}", $e->getTrace());
        }
    }

    public function delete(MessageReactionDTO $dto, string $account): void
    {
        try {
            $chatId = $dto->getChatId();
            $messageId = $dto->getMessageId();

            if ($chatId === null || $messageId === null) {
                return;
            }

            $this->messageReactionRepository->delete(
                $chatId,
                $messageId,
                $account
            );
        } catch (Throwable $e) {
            Log::error("Error when delete message reaction: {$e->getMessage()}", $e->getTrace());
        }
    }

}


