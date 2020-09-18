<?php

use Illuminate\Database\Seeder;
use App\User;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $user = User::create(
        //     [
        //         'name' => '小明',
        //         'email' => 'mail@gmail.com',
        //         'password' => Hash::make('111111')
        //     ]
        // );
        // $crm = factory(App\Crm::class,1)->craete()->each(function ($crm){
        //     $crm->save();
        // });

        factory(App\User::class)->create()->each(function ($user) {
            $user->crms()->save(factory(App\Crm::class)->make());
        });
        factory(App\User::class)->create(['email'=>'linyis@gmail.com','name'=>'JamesLin'])->each(function ($user) {
            $user->crms()->save(factory(App\Crm::class)->make());
        });
        factory(App\User::class)->create(['email'=>'test@test.com','name'=>'Test1'])->each(function ($user) {
            for ($i=0;$i<10;$i++)
                $user->crms()->save(factory(App\Crm::class)->make());
        });


    }
}
