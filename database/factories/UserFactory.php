<?php
// file: database/factories/UserFactory.php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

class UserFactory extends Factory
{
    protected $model = User::class;
    public function definition(): array
    {
        $name = $this->faker->name();
        return [
            'name' => $name,
            'username' => Str::slug($name.'-'.$this->faker->unique()->randomNumber(3)),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'),
        ];
    }
}

