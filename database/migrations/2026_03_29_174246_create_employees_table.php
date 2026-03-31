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
    Schema::create('employees', function (Blueprint $table) {
        $table->id();
        $table->string('full_name'); // සම්පූර්ණ නම
        $table->string('email')->unique(); // ඊමේල්
        $table->string('designation'); // තනතුර
        $table->decimal('salary', 10, 2); // වැටුප
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
        Schema::dropIfExists('employees');
    }
};
