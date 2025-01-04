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
        Schema::table('categories', function (Blueprint $table) {
            $table->comment('Post categories');
            $table->text('description')->after('name')->nullable()->comment('Brief description of the category');
            $table->text('scope')->after('description')->nullable()->comment('What is the scope of the category');
            $table->text('possible_contents')->after('scope')->nullable()->comment('Possible content that the category accepts');
            $table->text('post_suggestions')->after('possible_contents')->nullable()->comment('Post Suggestions for 30 Days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            //
        });
    }
};
