<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blade_templates', function (Blueprint $table) {
            $table->id();
            $table->string('class_name')->index();
            $table->string('template_name')->index();
            $table->string('template_path');
            $table->string('subject');
            $table->mediumText('body');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blade_templates');
    }
};
