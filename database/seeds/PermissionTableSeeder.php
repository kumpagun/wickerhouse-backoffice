<?php

use Illuminate\Database\Seeder;
use Maklad\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $role = new Permission();
      $role->name         = 'viewer';
      $role->display_name = 'Viewer'; // optional
      $role->description  = 'Description Viewer'; // optional
      $role->status       = 1;
      $role->save();

      $role = new Permission();
      $role->name         = 'editor';
      $role->display_name = 'Editor'; // optional
      $role->description  = 'Description Editor'; // optional
      $role->status       = 1;
      $role->save();
    }
}
