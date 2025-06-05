<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\{
    Site,
    SiteKeyword
};

class SitesImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collections)
    {
        foreach($collections as $collection)
        {
            $domain = $collection[0];


            if($domain)
            {
                $site = Site::where('domain',$domain)->first();

                $region = $collection[4];

                if(!$site)
                {
                    $site = Site::create([
                        'user_id' => auth()->user()->id,
                        'domain' => $domain,
                        'region' => $region,
                    ]);
                }

                $keywordUrl = $collection[1];
                $keywordName = $collection[2];
                $keywordClicksPerDay = $collection[3];

                if($keyword = $site->keywords()->where('name',$keywordName)->first())
                {
                    $keyword->update([
                        'clicks_per_day' => $keywordClicksPerDay
                    ]);
                }else{
                    $site->keywords()->create([
                        'name' => $keywordName,
                        'clicks_per_day' => $keywordClicksPerDay,
                        'url' => $keywordUrl
                    ]);
                }
            }
        }
    }
}
