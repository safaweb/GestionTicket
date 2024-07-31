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
            $table->unsignedBigInteger('pays_id')->nullable()->index('pays_id');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_contrat')->default(false);
            $table->date('start_date')->nullable(); // Add this line
            $table->date('end_date')->nullable(); // Add this line
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
                'is_active',
                'is_contrat',
                'start_date',
                'end_date',
                'phone',
                'deleted_at',
            ]);
        });
    }
};
