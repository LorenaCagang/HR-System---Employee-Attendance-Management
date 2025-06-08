<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class AddColumnsToEmployeesTable extends Migration
{
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('id');
            $table->string('middle_name')->nullable()->after('first_name');
            $table->string('suffix')->nullable()->after('last_name');
            $table->date('birthday')->nullable()->after('suffix');
            $table->string('contact_number')->nullable()->after('email');
            $table->text('remarks')->nullable()->after('contact_number');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('remarks');
        });
    }

    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'photo',
                'middle_name',
                'suffix',
                'birthday',
                'contact_number',
                'remarks',
                'gender',
            ]);
        });
    }
}
