<?php

use App\Models\RegionGeoData;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        RegionGeoData::findOrFail(142)->update(['name' => 'Eisden, Leut, Mechelen-aan-de-Maas, Meeswijk, Opgrimbie, Vucht, Boorsem, Uikhoven']);
        RegionGeoData::findOrFail(213)->update(["region_id" => 18]);
        RegionGeoData::findOrFail(1000)->update(['region_id' => 18]);
        RegionGeoData::findOrFail(346)->update(['region_id' => 19]);
    }
};
