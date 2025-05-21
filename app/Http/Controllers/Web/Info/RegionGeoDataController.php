<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Info;

use App\Models\RegionGeoData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class RegionGeoDataController
{
    #[Get(uri: '/api/geo-data')]
    public function __invoke(): JsonResponse
    {
        $geoFeatures = RegionGeoData::query()
            ->with('region')
            ->select('name', 'region_id', 'postal', DB::raw('ST_AsGeoJSON(geometry) as geometry_geojson'))
            ->get();

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => [],
        ];

        foreach ($geoFeatures as $feature) {
            $featureCollection['features'][] = [
                "type" => "Feature",
                "properties" => [
                    "name" => $feature->name,
                    'region_id' => $feature->region_id,
                    'region_name' => $feature->region->name,
                    "postal" => $feature->postal,
                ],
                "geometry" => json_decode((string) $feature->geometry_geojson),
            ];
        }

        // Return the data as a JSON response
        return response()->json($featureCollection);
    }
}
