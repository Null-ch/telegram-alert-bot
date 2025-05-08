<?php

namespace App\Http\Controllers\Web;

use App\Models\Report;
use App\Models\GroupChat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Common\BaseAppealService;
use App\Services\Common\BaseTelegramService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdminController extends Controller
{
    public BaseAppealService $baseAppealService;
    public BaseTelegramService $baseTelegramService;

    public function __construct(
        BaseAppealService $baseAppealService,
        BaseTelegramService $baseTelegramService,
    ) {
        $this->baseAppealService = $baseAppealService;
        $this->baseTelegramService = $baseTelegramService;
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

    public function test(Request $request) {}
}
