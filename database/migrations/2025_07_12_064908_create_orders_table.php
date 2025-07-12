<?php

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string("barcode")->default('1234567890');
            $table->integer("subtotal");
            $table->integer("shipping")->default(0);
            $table->integer("discount")->default(0);
            $table->integer("total");
            $table->tinyInteger("placed")->default(0);
            $table->dateTime("delivery_eta")->nullable();
            $table->foreignIdFor(Address::class)->nullable();
            $table->foreignIdFor(User::class)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
