<?php

use App\Models\Cart;
use App\Models\Product;
use App\Models\Variant;
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
        Schema::create('cart_variant', function (Blueprint $table) {
            $table->foreignIdFor(Cart::class);
            $table->foreignIdFor(Variant::class);
            $table->integer('quantity')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_variant');
    }
};
