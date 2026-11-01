<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name')->nullable();
            $table->string('type')->default('percent'); // percent, fixed
            $table->decimal('value', 10, 2)->default(0);
            $table->decimal('max_discount', 10, 2)->nullable();
            $table->decimal('min_order_amount', 10, 2)->default(0);
            $table->integer('usage_limit')->nullable();
            $table->integer('used_count')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Seed default academy scholarship & promo coupons
        $defaultCoupons = [
            [
                'code' => 'AGNIVEER50',
                'name' => 'Special Agniveer 50% Defence Scholarship',
                'type' => 'percent',
                'value' => 50.00,
                'max_discount' => 5000.00,
                'min_order_amount' => 0.00,
                'usage_limit' => null,
                'used_count' => 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'OFFICER20',
                'name' => 'Officer Aspirants 20% Merit Concession',
                'type' => 'percent',
                'value' => 20.00,
                'max_discount' => 2000.00,
                'min_order_amount' => 0.00,
                'usage_limit' => null,
                'used_count' => 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'WARRIOR15',
                'name' => 'Defence Personnel Dependent 15% Concession',
                'type' => 'percent',
                'value' => 15.00,
                'max_discount' => 1500.00,
                'min_order_amount' => 0.00,
                'usage_limit' => null,
                'used_count' => 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'NIRMAAN10',
                'name' => 'Nirmaan Early Bird 10% Concession',
                'type' => 'percent',
                'value' => 10.00,
                'max_discount' => 1000.00,
                'min_order_amount' => 0.00,
                'usage_limit' => null,
                'used_count' => 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'CADET100',
                'name' => 'Param Vishisht 100% Full Academy Scholarship',
                'type' => 'percent',
                'value' => 100.00,
                'max_discount' => 99999.00,
                'min_order_amount' => 0.00,
                'usage_limit' => null,
                'used_count' => 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('coupons')->insert($defaultCoupons);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
