<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Common\BaseAppealService;
use App\Services\Common\BaseTelegramService;

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
        $appeals = $this->baseAppealService->getAppeals(10, 'DESC');
        return view('admin.main.index', compact('appeals'));
    }
    public function sendMailing(Request $request)
    {
        $this->baseTelegramService->sendMailing($request->get('message'), $request->get('account'));
        return redirect('admin/resource/mailing-resource/mailing-index-page');
    }
}
