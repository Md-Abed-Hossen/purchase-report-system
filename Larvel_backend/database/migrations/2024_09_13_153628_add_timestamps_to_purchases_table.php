<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            if (!Schema::hasColumn('purchases', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }
            if (!Schema::hasColumn('purchases', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }
    

public function down()
{
    Schema::table('purchases', function (Blueprint $table) {
        $table->dropTimestamps();
    });
}
};
