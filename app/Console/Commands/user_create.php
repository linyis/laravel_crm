<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class user_create extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {email} {passwd=111111} {name=NONAME}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'user create by command';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::table('users')->insert([
            [
                'email' => $this->argument('email'),
                'password' => Hash::make($this->argument('passwd')),
                'name' => $this->argument('name'),
            ],
        ]);
    }
}
