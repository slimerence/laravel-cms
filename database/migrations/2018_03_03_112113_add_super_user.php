<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;

class AddSuperUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        User::create([
            'name'=>'John Doe',
            'uuid'=>\Ramsey\Uuid\Uuid::uuid4()->toString(),
            'email'=>'admin@admin.com',
            'password'=>Hash::make('123456'),
            'role'=>'1',
            'group_id'=>'0',
            'phone'=>'12345678',
            'fax'=>'12345678',
            'address'=>'Level 26, 1 Bligh Street',
            'city'=>'Sydney',
            'state'=>'NSW',
            'postcode'=>'2000',
            'country'=>'Australia',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
