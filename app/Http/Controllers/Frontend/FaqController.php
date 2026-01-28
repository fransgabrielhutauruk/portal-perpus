<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\FaqService;

class FaqController extends Controller
{
    public function index()
    {
        $content = FaqService::getIndexContent();
        $pageConfig = FaqService::getPageConfig();

        return view('contents.frontend.pages.faq.index', compact('content', 'pageConfig'));
    }
}
