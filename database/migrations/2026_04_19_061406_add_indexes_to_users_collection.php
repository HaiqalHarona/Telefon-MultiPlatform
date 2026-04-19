<?php

use Illuminate\Database\Migrations\Migration;
use MongoDB\Laravel\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $collection) {
            $collection->sparse_and_unique('email');
            $collection->sparse_and_unique('google_id');
            $collection->sparse_and_unique('github_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $collection) {
            $collection->dropIndexIfExists('email_1');
            $collection->dropIndexIfExists('google_id_1');
            $collection->dropIndexIfExists('github_id_1');
        });
    }
};
