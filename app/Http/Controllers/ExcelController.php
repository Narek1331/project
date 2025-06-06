<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteKeyword;

class ExcelController extends Controller
{
    /**
     * Display the list of site keywords for the authenticated user.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $userId = base64_decode($request->token);

        $siteKeywords = SiteKeyword::with('site')
        ->whereHas('site', function ($query) use ($userId) {
            $query->where('user_id', $userId)
                ->where('status', true);
        })
        ->get();

        $data = $siteKeywords->map(function ($keyword) {
            return [
                'domain' => $keyword->site->domain,
                'url' => $keyword->url ?? '',
                'name' => $keyword->name ?? '',
                'clicks_per_day' => $keyword->clicks_per_day ?? 0,
                'region' => $keyword->site->region,
            ];
        });

        return view('excel-editor', ['data' => $data]);
    }
}
