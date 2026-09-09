<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
        {
            Schema::table('assets', function (Blueprint $table) {
                // Kita set nullable() agar aset lama yang sudah ada tidak error
                $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            });
        }

    public function down()
        {
            Schema::table('assets', function (Blueprint $table) {
                $table->dropForeign(['location_id']);
                $table->dropForeign(['department_id']);
                $table->dropColumn(['location_id', 'department_id']);
            });
        }
};
