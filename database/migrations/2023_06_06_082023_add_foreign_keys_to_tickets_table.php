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
        Schema::table('tickets', function (Blueprint $table) {
            $table->foreign(['priority_id'], 'tickets_ibfk_1')->references(['id'])->on('priorities')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['projet_id'], 'tickets_ibfk_2')->references(['id'])->on('projets')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['owner_id'], 'tickets_ibfk_3')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['problem_category_id'], 'tickets_ibfk_4')->references(['id'])->on('problem_categories')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['responsible_id'], 'tickets_ibfk_5')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['statuts_des_tickets_id'], 'tickets_ibfk_6')->references(['id'])->on('statuts_des_tickets')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            //$table->foreign(['validation_id'], 'tickets_ibfk_7')->references(['id'])->on('validation')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            //$table->foreign(['qualification_id'], 'tickets_ibfk_8')->references(['id'])->on('qualification')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign('tickets_ibfk_1');
            $table->dropForeign('tickets_ibfk_2');
            $table->dropForeign('tickets_ibfk_3');
            $table->dropForeign('tickets_ibfk_4');
            $table->dropForeign('tickets_ibfk_5');
            $table->dropForeign('tickets_ibfk_6');
            //$table->dropForeign('tickets_ibfk_7');
            //$table->dropForeign('tickets_ibfk_8');
        });
    }
};
