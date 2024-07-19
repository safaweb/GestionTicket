<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('societe_id')->nullable()->index('societe_id');
            $table->unsignedBigInteger('pays_id')->nullable()->index('pays_id');
            $table->boolean('is_active')->default(true);
            $table->string('phone')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'societe_id',
                'pays',
                'is_active',
                'phone',
                'deleted_at',
            ]);
        });
    }
};
