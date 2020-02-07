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
      $role->name         = 'course';
      $role->display_name = 'Course'; // optional
      $role->description  = 'Description Course'; // optional
      $role->status       = 1;
      $role->save();

      $role = new Role();
      $role->name         = 'teacher';
      $role->display_name = 'Teacher'; // optional
      $role->description  = 'Description Teacher'; // optional
      $role->status       = 1;
      $role->save();

      $role = new Role();
      $role->name         = 'homework';
      $role->display_name = 'Homework'; // optional
      $role->description  = 'Description Homework'; // optional
      $role->status       = 1;
      $role->save();

      $role = new Role();
      $role->name         = 'question';
      $role->display_name = 'Question'; // optional
      $role->description  = 'Description Question'; // optional
      $role->status       = 1;
      $role->save();
    }
}
