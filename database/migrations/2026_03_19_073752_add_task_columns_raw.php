<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE tasks ADD COLUMN IF NOT EXISTS priority VARCHAR(10) NOT NULL DEFAULT 'medium'");
        DB::statement("ALTER TABLE tasks ADD COLUMN IF NOT EXISTS due_date DATE NULL");
        DB::statement("ALTER TABLE tasks ADD COLUMN IF NOT EXISTS category_id BIGINT NULL");
        DB::statement("ALTER TABLE tasks ADD COLUMN IF NOT EXISTS completed_at TIMESTAMP NULL");
        DB::statement("ALTER TABLE tasks ADD COLUMN IF NOT EXISTS deleted_at TIMESTAMP NULL");
        DB::statement("ALTER TABLE tasks DROP CONSTRAINT IF EXISTS tasks_status_check");
        DB::statement("ALTER TABLE tasks ADD CONSTRAINT tasks_status_check CHECK (status IN ('pending','in_progress','completed'))");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE tasks DROP COLUMN IF EXISTS priority");
        DB::statement("ALTER TABLE tasks DROP COLUMN IF EXISTS due_date");
        DB::statement("ALTER TABLE tasks DROP COLUMN IF EXISTS category_id");
        DB::statement("ALTER TABLE tasks DROP COLUMN IF EXISTS completed_at");
        DB::statement("ALTER TABLE tasks DROP COLUMN IF EXISTS deleted_at");
    }
};
