<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->foreignId('cafe_table_id')->nullable()->constrained('cafe_tables')->nullOnDelete();
            $table->string('zone_code', 50)->nullable();
            $table->index(['fecha', 'hora', 'status']);
            $table->string('status', 20)->default('pending')->change();
        });

        $tablesByCode = DB::table('cafe_tables')->get()->keyBy('code');
        $tablesByZoneName = $tablesByCode->keyBy('zone_name');
        $tablesByZoneCode = $tablesByCode->groupBy('zone');

        DB::table('reservations')->orderBy('id')->get()->each(function ($reservation) use ($tablesByCode, $tablesByZoneName, $tablesByZoneCode) {
            $table = $reservation->mesa_id
                ? $tablesByCode->get($reservation->mesa_id)
                : ($tablesByZoneName->get($reservation->zona)
                    ?? $tablesByZoneCode->get($reservation->zona)?->first());

            if (! $table) {
                return;
            }

            DB::table('reservations')->where('id', $reservation->id)->update([
                'cafe_table_id' => $reservation->mesa_id ? $table->id : null,
                'zone_code' => $table->zone,
            ]);
        });
    }

    public function down(): void
    {
        DB::table('reservations')->where('status', 'no_show')->update(['status' => 'cancelled']);

        Schema::table('reservations', function (Blueprint $table) {
            $table->dropForeign(['cafe_table_id']);
            $table->dropIndex(['fecha', 'hora', 'status']);
            $table->dropColumn(['cafe_table_id', 'zone_code']);
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending')->change();
        });
    }
};