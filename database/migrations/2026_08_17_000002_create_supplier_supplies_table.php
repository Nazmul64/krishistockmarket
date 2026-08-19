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
        Schema::create('supplier_supplies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id'); // FK to users.id
            $table->string('invoice_no');
            $table->string('product_name');
            $table->string('category')->nullable();
            $table->decimal('quantity', 12, 2);
            $table->string('unit'); // MT, KG, Bag, Piece, etc.
            $table->decimal('rate', 12, 2);
            $table->decimal('total_amount', 12, 2);
            $table->date('supply_date');
            $table->string('invoice_file')->nullable();
            $table->text('note')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_supplies');
    }
};
