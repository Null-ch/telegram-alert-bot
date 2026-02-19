<?php

namespace App\Http\Controllers\Web;

use App\Models\Report;
use App\Models\GroupChat;
use App\Models\MessageReaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Common\BaseAppealService;
use App\Services\Common\BaseTelegramService;
use App\Services\Common\BaseExportService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdminController extends Controller
{
    public BaseAppealService $baseAppealService;
    public BaseTelegramService $baseTelegramService;
    public BaseExportService $baseExportService;

    public function __construct(
        BaseAppealService $baseAppealService,
        BaseTelegramService $baseTelegramService,
        BaseExportService $baseExportService,
    ) {
        $this->baseAppealService = $baseAppealService;
        $this->baseTelegramService = $baseTelegramService;
        $this->baseExportService = $baseExportService;
    }

    public function index()
    {
        return redirect('/admin');
    }

    public function sendMailing(Request $request)
    {
        $this->baseTelegramService->sendMailing($request);
        return redirect('admin/resource/mailing-resource/mailing-index-page');
    }

    public function download($id): BinaryFileResponse
    {
        $report = Report::findOrFail($id);
        return response()->download($report->path);
    }

    public function generateReport(Request $request)
    {
        $reportId = $this->baseAppealService->generateReport($request);
        return redirect()->route('reports.download', $reportId);
    }

    public function getGroupChats(Request $request)
    {
        $account = $request->query('account');

        $groupChats = GroupChat::where('account', $account)
        ->orderBy('title', 'ASC')
            ->pluck('title', 'id')
            ->map(function ($title, $id) use ($account) {
                return "{$title} ({$account})";
            });

        return response()->json($groupChats);
    }

    public function exportMessageReactions(Request $request): BinaryFileResponse
    {
        $query = MessageReaction::with('employee');

        // Фильтр по дате применяется глобальным scope в модели MessageReaction
        $reactions = $query->orderBy('created_at', 'desc')->get();
        $exportArray = $this->baseExportService->prepareMessageReactionsArray($reactions->all());

        if (empty($exportArray)) {
            // Если данных нет, создаем пустой массив с заголовками
            $exportArray = [[
                'ID' => '',
                'Аккаунт' => '',
                'Сотрудник (ФИО)' => '',
                'Сотрудник (тег)' => '',
                'Дата' => '',
                'Реакция' => '',
            ]];
        }

        $filename = sprintf('message_reactions_%s.xlsx', now()->format('Ymd_His'));
        $filePath = $this->baseExportService->exportToExcel($exportArray, $filename);

        return response()->download($filePath, $filename)->deleteFileAfterSend(true);
    }

    public function test(Request $request) {}
}
