<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->longText('title')->nullable()->change();
            $table->longText('meta_title')->nullable()->change();
            $table->longText('meta_description')->nullable()->change();
            $table->longText('content')->nullable()->change();
            $table->boolean('show_in_header')->default(false)->after('status');
            $table->boolean('show_in_footer')->default(false)->after('show_in_header');
            $table->unsignedInteger('sort_order')->default(0)->after('show_in_footer');
        });

        DB::table('pages')->orderBy('id')->get()->each(function (object $page): void {
            DB::table('pages')
                ->where('id', $page->id)
                ->update([
                    'title' => json_encode(['en' => $page->title], JSON_UNESCAPED_UNICODE),
                    'content' => json_encode(['en' => $page->content], JSON_UNESCAPED_UNICODE),
                    'meta_title' => json_encode(['en' => $page->meta_title], JSON_UNESCAPED_UNICODE),
                    'meta_description' => json_encode(['en' => $page->meta_description], JSON_UNESCAPED_UNICODE),
                ]);
        });
    }

    public function down(): void
    {
        DB::table('pages')->orderBy('id')->get()->each(function (object $page): void {
            $title = json_decode($page->title ?? '', true);
            $content = json_decode($page->content ?? '', true);
            $metaTitle = json_decode($page->meta_title ?? '', true);
            $metaDescription = json_decode($page->meta_description ?? '', true);

            DB::table('pages')
                ->where('id', $page->id)
                ->update([
                    'title' => $title['en'] ?? '',
                    'content' => $content['en'] ?? null,
                    'meta_title' => $metaTitle['en'] ?? null,
                    'meta_description' => $metaDescription['en'] ?? null,
                ]);
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->string('title', 255)->nullable(false)->change();
            $table->string('meta_title', 255)->nullable()->change();
            $table->string('meta_description', 500)->nullable()->change();
            $table->longText('content')->nullable()->change();
            $table->dropColumn(['show_in_header', 'show_in_footer', 'sort_order']);
        });
    }
};
