<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemittancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remittances', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->enum('paid', ['Y', 'N'])->default('N');
            $table->enum('type', ['На расчетный счет Сбербанк','В центральном кассе'])->default('На расчетный счет Сбербанк');
            $table->string('amount')->nullable();
            $table->timestamp('payed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('remittances');
    }
}
