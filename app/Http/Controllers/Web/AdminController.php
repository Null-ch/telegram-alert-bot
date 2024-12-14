<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Common\BaseAppealService;

class AdminController extends Controller
{
    public BaseAppealService $baseAppealService;

    public function __construct(BaseAppealService $baseAppealService)
    {
        $this->baseAppealService = $baseAppealService;
        $this->middleware('auth');
    }
    public function index()
    {
        $appeals = $this->baseAppealService->getAppeals(10, 'DESC');
        return view('admin.main.index', compact('appeals'));
    }
}
