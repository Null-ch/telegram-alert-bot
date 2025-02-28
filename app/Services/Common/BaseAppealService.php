<?php

namespace App\Services\Common;

use Throwable;
use Carbon\Carbon;
use App\DTO\AppealDTO;
use App\Models\Report;
use App\Enums\ChatType;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Repositories\AppealRepository;
use App\Interfaces\AppealServiceInterface;

class BaseAppealService implements AppealServiceInterface
{
    public AppealRepository $appealRepository;
    public BaseClientService $baseClientService;
    public BaseExportService $baseExportService;

    public function __construct(
        AppealRepository $appealRepository,
        BaseClientService $baseClientService,
        BaseExportService $baseExportService,
    ) {
        $this->appealRepository = $appealRepository;
        $this->baseClientService = $baseClientService;
        $this->baseExportService = $baseExportService;
    }

    public function createAppeal(array $appealDataArray): ?AppealDTO
    {
        try {
            $appealData = new AppealDTO(
                Arr::get($appealDataArray, 'text'),
                Arr::get($appealDataArray, 'chat'),
                Arr::get($appealDataArray, 'chatId'),
                Arr::get($appealDataArray, 'channelType'),
                Arr::get($appealDataArray, 'clientId'),
                Arr::get($appealDataArray, 'messageId'),
            );
            $this->appealRepository->create($appealData);

            return $appealData;
        } catch (Throwable $e) {
            Log::error("Error when create client: {$e->getMessage()}", $e->getTrace());
            return null;
        }
    }

    public function isExpiredTimeout(int|string $id, string $channelType, ?string $chat)
    {
        if (!$chat) {
            $chat = ChatType::private->value;
        }

        $lastAppeal = $this->appealRepository->getLastAppeal($id);

        if (!$lastAppeal) {
            return true;
        }

        $lastAppealCreatedAt = Carbon::parse($lastAppeal->created_at);
        $minutesPassed = $lastAppealCreatedAt->diffInMinutes(Carbon::now());

        if ($minutesPassed >= env('TIMEOUT_TO_NEXT_MESSAGE')) {
            return true;
        } else {
            return false;
        }
    }

    public function getAppeal(int $id): ?AppealDTO
    {
        return $this->appealRepository->getAppeal($id);
    }

    public function getAppeals(int $count, string $sort): ?array
    {
        return $this->appealRepository->getAppeals($count, $sort);
    }

    public function generateReport(Request $request)
    {
        $dateFrom = $request->input('период.from');
        $dateTo = $request->input('период.to');
        $appeals = $this->appealRepository->getAppealsByDateRange($dateFrom, $dateTo);
        $exportArray = $this->baseExportService->prepareAppealsArray($appeals);
        $filename = sprintf('appeal_report_%s.xlsx', now()->format('Ymd_His'));
        $filePath = $this->baseExportService->exportToExcel($exportArray, $filename);
        $reportId = $this->baseExportService->createReport([
            'title' => $filename,
            'path' => $filePath
        ]);

        return $reportId;
    }
}
