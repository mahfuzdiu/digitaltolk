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
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('translation_key');
            $table->char('locale', 5);
            $table->text('translated_value');

            // We define the basic table first
            $table->timestamps();
            $table->unique(['translation_key', 'locale']);
        });

        // Use DB::statement to add the Postgres-specific types that Laravel's Blueprint struggles with
        DB::statement('ALTER TABLE translations ADD COLUMN context_tag text[] DEFAULT \'{}\'');
        DB::statement('ALTER TABLE translations ADD COLUMN content_vector tsvector');

        // GIN Index for Tag searching
        DB::statement('CREATE INDEX idx_context_gin ON translations USING GIN (context_tag);');

        // GIN Index for Content searching
        DB::statement('CREATE INDEX idx_content_vector_gin ON translations USING GIN (content_vector);');

        // Trigger for the search index
        DB::statement("
            CREATE TRIGGER tsvector_update BEFORE INSERT OR UPDATE ON translations
            FOR EACH ROW EXECUTE PROCEDURE tsvector_update_trigger(content_vector, 'pg_catalog.simple', translated_value);
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
        DB::statement('DROP TRIGGER IF EXISTS tsvector_update ON translations;');
    }
};
