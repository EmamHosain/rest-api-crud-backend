<?php

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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->default('Laravel pos');
            $table->string('company_email')->default('laravelpos@gmail.com');
            $table->string('company_address')->default('Laravel pos address');
            $table->string('company_phone')->default('+880 1626691836');
            $table->string('company_fax')->default('+880 34534');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
