<?php

use Illuminate\Database\Seeder;
use Maklad\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $role = new Role();
      $role->name         = 'admin';
      $role->display_name = 'Admin'; // optional
      $role->description  = 'Description Admin'; // optional
      $role->status       = 1;
      $role->save();

      $role = new Role();
      $role->name         = 'content';
      $role->display_name = 'Conent'; // optional
      $role->description  = 'Description Conent'; // optional
      $role->status       = 1;
      $role->save();

      $role = new Role();
      $role->name         = 'teacher';
      $role->display_name = 'Teacher'; // optional
      $role->description  = 'Description Teacher'; // optional
      $role->status       = 1;
      $role->save();
    }
}
