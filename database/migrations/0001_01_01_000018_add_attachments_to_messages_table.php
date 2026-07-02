<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttachmentsToMessagesTable extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {

            $table->string('file_path')->nullable()->after('message_text');
            $table->string('file_type')->nullable()->after('file_path'); 
            $table->text('message_text')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['file_path', 'file_type']);
        });
    }
}