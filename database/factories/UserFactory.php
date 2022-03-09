<?php

namespace Database\Factories\Model;

use App\Model\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

class UserFactory extends Factory {

  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = User::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition() {
    return [
      'name' => $this->faker->name,
      'fullname' => $this->faker->name,
      'country' => $this->faker->country,
      'city' => $this->faker->city,
      'street' => $this->faker->streetAddress,
      'email' => $this->faker->unique()->safeEmail,
      'gender' => collect(['Male', 'Female'])->random(),
      'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
      'remember_token' => str_random(10),
    ];
  }
}

/*$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'fullname' => $faker->name,
        'country' => $faker->country,
        'city' => $faker->city,
        'street' => $faker->streetAddress,
        'email' => $faker->unique()->safeEmail,
        'gender' => collect(['Male','Female'])->random(),
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
    ];
});*/
