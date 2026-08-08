<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_infos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('e_nid_number')->nullable();
            $table->string('e_nid_img')->nullable();
            $table->string('e_bath_number')->nullable();
            $table->string('e_bath_img')->nullable();
            $table->string('e_office_id_number')->nullable();
            $table->string('e_office_id_img')->nullable();
            $table->string('e_signature')->nullable();
            $table->string('e_cv')->nullable();
            $table->string('e_father_name')->nullable();
            $table->string('e_mother_name')->nullable();
            $table->string('e_gender')->nullable();
            $table->string('e_age')->nullable();
            $table->integer('status')->default('0');
            $table->integer('e_permission_id')->nullable();
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
        Schema::dropIfExists('employee_infos');
    }
}
