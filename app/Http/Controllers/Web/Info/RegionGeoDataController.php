<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Info;

use App\Models\RegionGeoData;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class RegionGeoDataController
{
    #[Get(uri: '/api/geo-data')]
    public function __invoke(): JsonResponse
    {
        $featureCollection = Cache::rememberForever('region_geo_data_feature_collection', function () {
            $geoFeatures = RegionGeoData::query()
                ->with('region')
                ->select('name', 'region_id', 'postal', DB::raw('ST_AsGeoJSON(geometry) as geometry_geojson'))
                ->get();

            $collection = [
                "type" => "FeatureCollection",
                "features" => [],
            ];

            collect($geoFeatures)->each(function($feature) {
                $collection['features'][] = [
                    "type" => "Feature",
                    "properties" => [
                        "name" => $feature->name,
                        'region_id' => $feature->region_id,
                        'region_name' => $feature->region->name,
                        "postal" => $feature->postal,
                    ],
                    "geometry" => json_decode((string) $feature->geometry_geojson),
                ];
            });

            return $collection;
        });

        // Return the data as a JSON response
        return response()->json($featureCollection);
    }
}
