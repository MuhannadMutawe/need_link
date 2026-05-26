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
        Schema::create('category_request', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignUlid('request_id')->constrained('requests')->cascadeOnDelete();
            $table->timestamps();
        });

        // Migrate existing data
        if (\Illuminate\Support\Facades\Schema::hasColumn('requests', 'category_id')) {
            $requests = \Illuminate\Support\Facades\DB::table('requests')->whereNotNull('category_id')->get();
            foreach($requests as $req) {
                \Illuminate\Support\Facades\DB::table('category_request')->insert([
                    'category_id' => $req->category_id,
                    'request_id' => $req->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            Schema::table('requests', function (Blueprint $table) {
                $table->dropForeign(['category_id']);
                $table->dropColumn('category_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained('categories')->cascadeOnDelete();
        });

        $relations = \Illuminate\Support\Facades\DB::table('category_request')->get();
        foreach($relations as $rel) {
            \Illuminate\Support\Facades\DB::table('requests')->where('id', $rel->request_id)->update(['category_id' => $rel->category_id]);
        }

        Schema::dropIfExists('category_request');
    }
};
