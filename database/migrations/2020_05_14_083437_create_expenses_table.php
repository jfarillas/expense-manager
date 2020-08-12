<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('expense_date', 0);
            $table->unsignedBigInteger('categories_id');
            $table->enum('type', ['cash', 'card', 'accounts']);
            $table->decimal('amount', 8, 2);
            $table->longText('description');
            $table->timestamps();

            $table->foreign('categories_id')
            ->references('id')->on('categories')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expenses');
    }
}
