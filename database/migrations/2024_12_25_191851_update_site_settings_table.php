<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSiteSettingsTable extends Migration
{
    public function up()
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $columnsToDrop = array_filter(['key', 'value'], function ($col) {
                return Schema::hasColumn('site_settings', $col);
            });
            if (!empty($columnsToDrop)) {
                $table->dropColumn(array_values($columnsToDrop));
            }

            if (!Schema::hasColumn('site_settings', 'country')) {
                $table->string('country')->nullable();
            }
            if (!Schema::hasColumn('site_settings', 'currency_symbol')) {
                $table->string('currency_symbol', 10)->nullable();
            }
            if (!Schema::hasColumn('site_settings', 'currency_code')) {
                $table->string('currency_code', 10)->nullable();
            }
            if (!Schema::hasColumn('site_settings', 'created_at')) {
                $table->timestamps();
            }
        });
    }

    public function down()
    {
        Schema::table('site_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('site_settings', 'key')) {
                $table->string('key')->nullable();
            }
            if (!Schema::hasColumn('site_settings', 'value')) {
                $table->text('value')->nullable();
            }
            if (!Schema::hasColumn('site_settings', 'created_at')) {
                $table->timestamps();
            }

            $columnsToDrop = array_filter(['country', 'currency_symbol', 'currency_code'], function ($col) {
                return Schema::hasColumn('site_settings', $col);
            });
            if (!empty($columnsToDrop)) {
                $table->dropColumn(array_values($columnsToDrop));
            }
        });
    }
}

