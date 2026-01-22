<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Frontend\SafeDataService;
use App\Services\Frontend\ContactService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ContactFormMail;

class InformationController extends Controller
{
    public function contact()
    {
        $content = SafeDataService::safeExecute(
            fn() => ContactService::getContent(),
            SafeDataService::getContactFallbacks()->content
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => ContactService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.information.contact', compact(
            'content',
            'pageConfig'
        ));
    }

    public function submitContactForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Send email
        try {
            Mail::to(config('mail.from.address'))->send(new ContactFormMail($request->all()));
            return response()->json(['success' => true, 'message' => 'Pesan Anda berhasil terkirim!']);
        } catch (\Exception $e) {
            Log::error('Failed to send contact form email: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat mengirim pesan. Silakan coba lagi.'], 500);
        }
    }

    public function faq()
    {
        return view('contents.frontend.pages.information.faq');
    }

    public function profilPerpustakaan()
    {
        $pageConfig = [
            'seo' => [
                'title' => 'Profil Perpustakaan',
                'description' => 'Profil dan sejarah Perpustakaan Politeknik Caltex Riau',
            ]
        ];

        return view('contents.frontend.pages.about.profil', compact('pageConfig'));
    }

    public function pustakawan()
    {
        $pustakawanList = \App\Models\Pustakawan::select(['pustakawan_id', 'nama', 'email', 'foto'])
            ->whereNull('deleted_at')
            ->orderBy('nama', 'ASC')
            ->get();

        $pageConfig = [
            'seo' => [
                'title' => 'Pustakawan - Perpustakaan PCR',
                'description' => 'Tim Pustakawan Perpustakaan Politeknik Caltex Riau',
            ]
        ];

        return view('contents.frontend.pages.about.pustakawan', compact('pageConfig', 'pustakawanList'));
    }

    public function jamBuka()
    {
        $jadwal = [
            [
                'hari' => 'Senin - Kamis',
                'jam' => '08.00 - 16.00 WIB',
                'icon' => 'fa-solid fa-calendar-days',
                'color' => 'primary'
            ],
            [
                'hari' => 'Jumat',
                'jam' => '08.00 - 16.30 WIB',
                'icon' => 'fa-solid fa-calendar-day',
                'color' => 'success'
            ],
            [
                'hari' => 'Sabtu - Minggu',
                'jam' => 'Libur',
                'icon' => 'fa-solid fa-calendar-xmark',
                'color' => 'danger'
            ]
        ];

        $pageConfig = [
            'seo' => [
                'title' => 'Jam Buka Layanan - Perpustakaan PCR',
                'description' => 'Jam Buka Layanan Perpustakaan Politeknik Caltex Riau',
            ]
        ];

        return view('contents.frontend.pages.about.jam-buka', compact('pageConfig', 'jadwal'));
    }

    public function shop()
    {
        return view('contents.frontend.pages.information.shop.index');
    }

    public function shopDetail($id)
    {
        return view('contents.frontend.pages.information.shop.show', ['id' => $id]);
    }
}
