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
      $role->name         = 'guest';
      $role->display_name = 'Guest'; // optional
      $role->description  = 'Description Guest'; // optional
      $role->status       = 1;
      $role->save();

      $role = new Role();
      $role->name         = 'general';
      $role->display_name = 'General'; // optional
      $role->description  = 'Description General'; // optional
      $role->status       = 1;
      $role->save();

      $role = new Role();
      $role->name         = 'report';
      $role->display_name = 'Report'; // optional
      $role->description  = 'Description Report'; // optional
      $role->status       = 1;
      $role->save();
    }
}
