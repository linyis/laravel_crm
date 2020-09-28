<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Category;
use App\Socialkey;
use Illuminate\Support\Facades\DB;

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
            for ($i=0;$i<10;$i++) {
                $user->crms()->save(factory(App\Crm::class)->make());

            }

        });
// 產品
        factory(App\Orders\Goods::class,40)->create();
        // factory(App\Orders\Goods::class)->create(10)->each(function ($good) {

        // });


// 類別
        $areaTree = [
            ['name' => '精華',
                'children' => [
                    ['name' => '新聞',
                        'children' => [
                             ['name' => '綠色'],
                             ['name' => '藍色']
                        ]],
                    ['name' => '網路消息']
            ]],
        ];

        Category::buildTree($areaTree); // => true

//  Insert LINE KEY
        SocialKey::insert([
            "name"=>"LINE",
            "channel"=>"1649018310",
            "key"=>"1f7cbab35d61178e6278c092f3edd84e"
            ]
        );
        for ($i=0;$i<34;$i++) {
            $cat = random_int(1,5);
            $crms = random_int(1,33);
            if (!DB::select('select * from category_crm where category_id = ? and crm_id = ?', [$cat,$crms]))
                DB::insert('insert into category_crm (category_id, crm_id) values (?, ?)', [$cat, $crms]);
        }
    }
}
