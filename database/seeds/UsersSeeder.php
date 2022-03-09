<?php

namespace Database\Seeders;

use App\Model\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder {

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {
    User::factory()
      ->create(
        [
          'email' => 'test@gmail.coom',
        ]
      );
    User::factory()
      ->create(
        [
          'email' => 'test2@gmail.coom',
        ]
      );

    User::factory()
      ->count(48)
      ->create();
  }
}
