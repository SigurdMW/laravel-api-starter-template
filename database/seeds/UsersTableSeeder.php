<?php 

use Illuminate\Database\Seeder;
use App\User;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder {
	
	public function run()
	{

		$faker = Faker::create();

		foreach(range(1,30) as $index)
		{
			User::create([
				'name' 		=> $faker->name,
				'email' 	=> $faker->email,
				'password' 	=> bcrypt($faker->word),
			]);
		}

		User::create([
			'name' 		=> 'Sigurd',
			'email' 	=> 'sigurd@me.com',
			'password' 	=> bcrypt('Krekar01'),
		]);

	}

}