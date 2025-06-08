<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDepartmentHeadToEmployeesInDepartments extends Migration
{
    public function up()
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropForeign(['dept_head_id']);
            $table->foreign('dept_head_id')->references('id')->on('employees')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropForeign(['dept_head_id']);
            $table->foreign('dept_head_id')->references('id')->on('users')->onDelete('set null');
        });
    }
}