<?php

use App\Company;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->insert([
            'names' => 'Google',
            'address' => 'California EEUU',
            'nit' => '900214217',
            'phone' => '555-555-5555',
            'email' => 'support@google.com',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        DB::table('companies')->insert([
            'names' => 'La Pagina',
            'address' => 'Tachira Venezuela',
            'nit' => '914444927',
            'phone' => '141-014-1518',
            'email' => 'webmaster@lapagina.com',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        DB::table('companies')->insert([
            'names' => 'Respuestos',
            'address' => 'Corder Venezuela',
            'nit' => '8198233555',
            'phone' => '426-987-6589',
            'email' => 'contacto@respuestos.com',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
