<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentsTable extends Migration
{
    public function up()
{
    if (!Schema::hasTable('departments')) {
        Schema::create('departments', function (Blueprint $table) {
            $table->id()->unsigned()->autoIncrement();
            $table->string('name', 150)->notNull();
            $table->unsignedBigInteger('dept_head_id')->nullable();
            $table->string('profile_picture')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->text('description')->nullable(); // Added description field
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->foreign('dept_head_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }
}
    public function down()
    {
        Schema::dropIfExists('departments');
    }
}