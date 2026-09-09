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
        Schema::table('maintenance_tickets', function (Blueprint $table) {
            // Menambahkan kolom foto setelah kolom deskripsi
            $table->string('photo_path')->nullable()->after('issue_description');
        });
    }

    public function down()
    {
        Schema::table('maintenance_tickets', function (Blueprint $table) {
            $table->dropColumn('photo_path');
        });
    }
};
