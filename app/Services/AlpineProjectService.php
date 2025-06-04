<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use App\Models\{
    Site,
    AdminParam
};

class AlpineProjectService
{
    protected Client $client;
    public $userBalanceService;

    public function __construct()
    {
        $this->userBalanceService = app(UserBalanceService::class);
        $this->client = new Client([
            'base_uri' => 'https://alpine-project.ru',
            'timeout'  => 10.0,
        ]);
    }

    public function syncClickCounts(): void
    {
        try {
            $datas = $this->getSiteList()['data'];

            if($datas)
            {
                foreach($datas as $data)
                {
                    $site = Site::where('domain',$data['domain'])->first();

                    if($site)
                    {
                        $remainingAmount = $data['click_in_hour'] - $site->click_in_hour;
                        $adminParam = AdminParam::first();
                        $oneClickPrice = $adminParam->one_click_price;
                        $totalCoast = $remainingAmount * $oneClickPrice;


                        if($totalCoast)
                        {
                            $this->userBalanceService->decreaseByUserId($site->user_id,$totalCoast);
                        }


                        $site->update([
                            "click_in_hour" => $data['click_in_hour'],
                            "click_per_day" => $data['click_per_day'],
                            "clean_click_in_hour" => $data['clean_click_in_hour'],
                            "clean_click_per_day" => $data['clean_click_per_day'],
                        ]);
                    }

                }
            }



        } catch (\Throwable $e) {

        }
    }

    private function getSiteList()
    {
        $response = $this->client->get('/api/site_list.php', [
            'query' => [
                'username' => env('ALPINE_USERNAME'),
                'password' => env('ALPINE_PASSWORD'),
            ],
        ]);

        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);

        if (!is_array($data)) {
           return null;
        }

        return $data;
    }
}
