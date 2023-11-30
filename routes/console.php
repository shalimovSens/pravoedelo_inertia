<?php

use App\Models\Office;
use App\Models\Remittance;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Faker\Generator as Faker;
use Illuminate\Support\Str;


/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('downloadFakeDataInBase', function (Faker $faker) {
    $roles = [
        ['name' => 'Manager'],
        ['name' => 'Lawyer'],
        ['name' => 'Client']
    ];
    foreach ($roles as $role) {
        Role::create($role);
    }
    $offices = [
        ['name' => 'Ростов'],
        ['name' => 'Воронеж'],
        ['name' => 'Москва'],
        ['name' => 'Иркутск']
    ];
    foreach ($offices as $office) {
        Office::create($office);
    }
    $manager1 = User::create([
        'first_name' => $faker->firstName(),
        'middle_name' => $faker->firstName(),
        'last_name' => $faker->lastName(),
        'email' => '123123@mail.ru',
        'email_verified_at' => Carbon::parse('2022-12-29 15:50:40'),
        'manager_id' => 0,
        'lawyer_id' => 0,
        'price' => 0,
        'type' => 'Нет',
        'payment_per_month' => 0,
        'plan' => '',
        'first_date' => '',
        'second_date' => '',
        'password' => bcrypt('123456'),
        'date_registration' => Carbon::parse('2022-12-29 15:50:40'),
        'remember_token' => Str::random(10)
    ]);
    $manager1->roles()->sync(1);
    $manager1->account()->create([
        'office_id' => 1,
        'position' => 'Менеджер'
    ]);
    $manager2 = User::create([
        'first_name' => $faker->firstName(),
        'middle_name' => $faker->firstName(),
        'last_name' => $faker->lastName(),
        'email' => 'manager2@mail.ru',
        'email_verified_at' => Carbon::parse('2022-12-29 15:50:40'),
        'manager_id' => 0,
        'lawyer_id' => 0,
        'price' => 0,
        'type' => 'Нет',
        'payment_per_month' => 0,
        'plan' => '',
        'first_date' => '',
        'second_date' => '',
        'password' => bcrypt('123456'),
        'date_registration' => Carbon::parse('2022-12-29 15:50:40'),
        'remember_token' => Str::random(10),
    ]);
    $manager2->roles()->sync(1);
    $manager2->account()->create([
        'office_id' => 2,
        'position' => 'Менеджер'
    ]);
    $lawyer1 = User::create([
        'first_name' => $faker->firstName(),
        'middle_name' => $faker->firstName(),
        'last_name' => $faker->lastName(),
        'email' => '999999@mail.ru',
        'email_verified_at' => Carbon::parse('2022-12-29 15:50:40'),
        'manager_id' => 0,
        'lawyer_id' => 0,
        'type' => 'Нет',
        'price' => 0,
        'payment_per_month' => 0,
        'plan' => '',
        'first_date' => '',
        'second_date' => '',
        'password' => bcrypt('123456'),
        'date_registration' => Carbon::parse('2022-12-29 15:50:40'),
        'remember_token' => Str::random(10),
    ]);
    $lawyer1->roles()->sync(2);
    $lawyer1->account()->create([
        'office_id' => Office::inRandomOrder()->limit(1)->first()->id,
        'position' => 'Юрист'
    ]);
    $lawyer2 = User::create([
        'first_name' => $faker->firstName(),
        'middle_name' => $faker->firstName(),
        'last_name' => $faker->lastName(),
        'email' => 'lawyer2@mail.ru',
        'email_verified_at' => Carbon::parse('2022-12-29 15:50:40'),
        'manager_id' => 0,
        'lawyer_id' => 0,
        'type' => 'Нет',
        'price' => 0,
        'payment_per_month' => 0,
        'plan' => '',
        'first_date' => '',
        'second_date' => '',
        'password' => bcrypt('123456'),
        'date_registration' => Carbon::parse('2022-12-29 15:50:40'),
        'remember_token' => Str::random(10),
    ]);
    $lawyer2->roles()->sync(2);
    $lawyer2->account()->create([
        'office_id' => Office::inRandomOrder()->limit(1)->first()->id,
        'position' => 'Юрист'
    ]);
    $startTransfer = Carbon::parse('2023-03-01 15:50:40');
    $endTransfer = Carbon::parse('2023-06-27 15:50:40');
    $startRegistration = Carbon::parse('2023-01-01 00:00:00');
    $endRegistration = Carbon::parse('2023-02-27 15:50:40');
    for($i = 0; $i < 100; $i++) {
            $user = User::create([
                'first_name' => $faker->firstName(),
                'middle_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'email' => $faker->unique()->safeEmail(),
                'email_verified_at' => null,
                'manager_id' => $faker->randomElement([1, 2]),
                'lawyer_id' => $faker->randomElement([3, 4, 0]),
                'type' => $faker->randomElement(['Минимизация', 'Банкротство']),
                'price' => $faker->randomElement(['200000', '50000', '40000', '100000']),
                'payment_per_month' => $faker->randomElement(['10000', '15000', '8000']),
                'plan' => $faker->randomElement(['20000', '10000', '30000', '15000']),
                'first_date' => $faker->numberBetween(1, 15),
                'second_date' => $faker->numberBetween(16, 31),
                'password' => bcrypt('password'),
                'transfer_stop' => $faker->dateTimeBetween($startTransfer, $endTransfer)->format('Y-m-d'),
                'date_registration' => $faker->dateTimeBetween($startRegistration, $endRegistration)->format('Y-m-d'),
                'remember_token' => Str::random(10),
            ]);
            $user->roles()->sync(3);
        }
    $clients = User::whereNotIn('email',['123123@mail.ru','999999@mail.ru','lawyer2@mail.ru','manager2@mail.ru'])->get();
    for ($i=0; $i < 200; $i++) { 
        Remittance::create([
            'user_id' => $clients->random()->id,
            'type' => $faker->randomElement(['На расчетный счет Сбербанк', 'В центральном кассе']),
            'paid' => $faker->randomElement(['Y','N']),
            'amount' => $faker->randomElement(['3000', '4000','5000', '1000', '10000']),
            'payed_at' => $faker->dateTimeBetween($startTransfer, $endTransfer)->format('Y-m-d')
        ]);
    }
})->purpose('OK');
