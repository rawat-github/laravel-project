<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE tasks ALTER COLUMN description DROP NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE tasks ALTER COLUMN description SET NOT NULL");
    }
};
