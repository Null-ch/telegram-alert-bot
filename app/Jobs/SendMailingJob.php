<?php

namespace App\Jobs;

use App\Services\Common\BaseTelegramService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMailingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Не повторять автоматически: часть чатов из предыдущей попытки могла уже получить сообщение,
     * повторный запуск отправил бы им рассылку ещё раз.
     */
    public int $tries = 1;

    public int $timeout = 900;

    /**
     * @param  int[]  $chatIds
     */
    public function __construct(
        public int $mailingId,
        public string $account,
        public string $message,
        public array $chatIds,
        public ?string $filePath = null,
        public ?string $fileOriginalName = null,
        public ?string $fileMimeType = null,
    ) {
    }

    public function handle(BaseTelegramService $telegramService): void
    {
        $telegramService->processMailing(
            $this->mailingId,
            $this->account,
            $this->message,
            $this->chatIds,
            $this->filePath,
            $this->fileOriginalName,
            $this->fileMimeType,
        );
    }
}
