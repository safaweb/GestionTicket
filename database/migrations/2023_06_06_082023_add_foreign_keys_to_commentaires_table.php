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
        Schema::table('commentaires', function (Blueprint $table) {
            $table->foreign(['user_id'], 'commentaires_ibfk_2')->references(['id'])->on('users')->onUpdate('NO ACTION');
            $table->foreign(['ticket_id'], 'commentaires_ibfk_3')->references(['id'])->on('tickets')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['user_id'], 'commentaires_ibfk_4')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('commentaires', function (Blueprint $table) {
            $table->dropForeign('commentaires_ibfk_2');
            $table->dropForeign('commentaires_ibfk_3');
            $table->dropForeign('commentaires_ibfk_4');
        });
    }
};
