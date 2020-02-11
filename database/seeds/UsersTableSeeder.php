<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $newUser = new User;
      $newUser->name = 'Admin';
      $newUser->username = 'admin';
      $newUser->password = Hash::make('admin');
      $newUser->status = 1;
      $newUser->save();
      $newUser->syncRoles(['admin']);
      $newUser->syncPermissions(['viewer','editor']);
    }
}
