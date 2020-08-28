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

      // $newUser = new User;
      // $newUser->name = 'Guest';
      // $newUser->username = 'guest';
      // $newUser->password = Hash::make('guest');
      // $newUser->status = 1;
      // $newUser->save();
      // $newUser->syncRoles(['guest']);
      // $newUser->syncPermissions(['viewer','editor']);

      // $newUser = new User;
      // $newUser->name = 'General';
      // $newUser->username = 'general';
      // $newUser->password = Hash::make('general');
      // $newUser->status = 1;
      // $newUser->save();
      // $newUser->syncRoles(['general']);
      // $newUser->syncPermissions(['viewer','editor']);
    }
}
