<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteKeyword;
use App\Models\User;

class ExcelController extends Controller
{
    /**
     * Display the list of site keywords for the authenticated user.
     *
     * @return \Illuminate\View\View
     */
    // public function index(Request $request): \Illuminate\View\View
    // {
    //     $token = $request->token;

    //      if (!$token) {
    //         abort(404);
    //     }

    //     $user = User::where('excel_token',$token)->first();
    //     $userId = $user->id;

    //     $siteKeywords = SiteKeyword::with('site')
    //     ->whereHas('site', function ($query) use ($userId) {
    //         $query->where('user_id', $userId)
    //             ->where('status', true);
    //     })
    //     ->get();

    //     $data = $siteKeywords->map(function ($keyword) {
    //         return [
    //             'domain' => $keyword->site->domain,
    //             'url' => $keyword->url ?? '',
    //             'name' => $keyword->name ?? '',
    //             'clicks_per_day' => $keyword->clicks_per_day ?? 0,
    //             'region' => $keyword->site->region,
    //             'yandex' => 'Яндекс',
    //         ];
    //     });

    //     return view('excel-editor', ['data' => $data]);
    // }

     public function index(Request $request)
    {
        $token = $request->token;

        if (!$token) {
            abort(404);
        }

        $user = User::where('excel_token', $token)->first();

        if (!$user) {
            abort(404);
        }

        $userId = $user->id;

        $siteKeywords = SiteKeyword::with('site')
            ->whereHas('site', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->where('status', true);
            })
            ->get();

        $lines = $siteKeywords->map(function ($keyword) {
            $domain = $keyword->site->domain;
            $name = $keyword->name ?? '';
            $url = $keyword->url ?? '';
            $clicks = $keyword->clicks_per_day ?? 0;
            $region = $keyword->site->region;
            $yandex = 'Яндекс';

            return "{$domain}:{$url}:{$name}:{$clicks}:{$region}:{$yandex}";
        });

        $content = $lines->implode("\n");

        return response($content)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
