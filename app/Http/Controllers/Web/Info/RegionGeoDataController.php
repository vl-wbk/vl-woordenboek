<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Info;

use App\Models\Region;
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

            foreach ($geoFeatures as $feature) {
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
            }

            return $collection;
        });

        // Return the data as a JSON response
        return response()->json($featureCollection);
    }

    #[Get(uri: '/api/geo-data/{region}')]
    public function show(Region $region): JsonResponse
    {
        // Construct a unique cache key based on the region identifier
        $cacheKey = 'region_geo_data_feature_collection_' . $region->id;

        $featureCollection = Cache::rememberForever($cacheKey, function () use ($region): array {
            $geoFeatures = RegionGeoData::query()
                ->with('region')
                // Add a where clause to filter by region
                ->whereHas('region', function ($query) use ($region): void {
                    // You'll need to decide how to identify the region.
                    // Options:
                    // 1. By region ID: $query->where('id', $regionIdentifier);
                    // 2. By region name/slug: $query->where('slug', $regionIdentifier);
                    // 3. By region name (case-insensitive): $query->whereRaw('LOWER(name) = ?', [strtolower($regionIdentifier)]);
                    // For this example, let's assume 'slug' or 'name' is used for the identifier in the URL.
                    // Adjust this line based on your 'region' model's identifiable attribute.
                    $query->where('id', $region->id); // Allow both slug and ID
                })
                ->select('name', 'region_id', 'postal', DB::raw('ST_AsGeoJSON(geometry) as geometry_geojson'))
                ->get();

            $collection = [
                "type" => "FeatureCollection",
                "features" => [],
            ];

            collect($geoFeatures)->each(function ($feature) use (&$collection): void {
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

        // If no features are found for the given region, you might want to return an empty collection
        // or a 404 response, depending on your API's desired behavior.
        if (empty($featureCollection['features'])) {
            // Option 1: Return an empty feature collection
            return response()->json($featureCollection);
            // Option 2: Return a 404 Not Found response
            // abort(404, 'Region geo-data not found.');
        }

        // Return the data as a JSON response
        return response()->json($featureCollection);
    }
}
