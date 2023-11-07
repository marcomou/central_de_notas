<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public static $STATE_CODES = [
        'RO' => '11',
        'AC' => '12',
        'AM' => '13',
        'RR' => '14',
        'PA' => '15',
        'AP' => '16',
        'TO' => '17',

        'MA' => '21',
        'PI' => '22',
        'CE' => '23',
        'RN' => '24',
        'PB' => '25',
        'PE' => '26',
        'AL' => '27',
        'SE' => '28',
        'BA' => '29',

        'MG' => '31',
        'ES' => '32',
        'RJ' => '33',
        'SP' => '35',

        'PR' => '41',
        'SC' => '42',
        'RS' => '43',

        'MS' => '50',
        'MT' => '51',
        'GO' => '52',
        'DF' => '53',
    ];

    public static $LOCATIONS = [
        "Região Norte"        => [
            'RO' => ['Rondônia'],
            'AC' => ['Acre'],
            'AM' => ['Amazonas'],
            'RR' => ['Roraima'],
            'PA' => ['Pará'],
            'AP' => ['Amapá'],
            'TO' => ['Tocantins'],
        ],

        "Região Nordeste"     => [
            'MA' => ['Maranhão'],
            'PI' => ['Piauí'],
            'CE' => ['Ceará'],
            'RN' => ['Rio Grande do Norte'],
            'PB' => ['Paraíba'],
            'PE' => ['Pernambuco'],
            'AL' => ['Alagoas'],
            'SE' => ['Sergipe'],
            'BA' => ['Bahia'],
        ],
        "Região Sudeste"      => [
            'MG' => ['Minas Gerais'],
            'ES' => ['Espírito Santo'],
            'RJ' => ['Rio de Janeiro'],
            'SP' => ['São Paulo'],
        ],
        "Região Sul"          => [
            'PR' => ['Paraná'],
            'SC' => ['Santa Catarina'],
            'RS' => ['Rio Grande do Sul'],
        ],
        "Região Centro-Oeste" => [
            'MS' => ['Mato Grosso do Sul'],
            'MT' => ['Mato Grosso'],
            'GO' => ['Goiás'],
            'DF' => ['Distrito Federal'],
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::$LOCATIONS as $region => $states) {

            foreach ($states as $key => $value) {
                Location::updateOrCreate([
                    'code' => self::$STATE_CODES[$key],
                ], [
                    'acronym' => $key,
                    'name' => $value[0],
                    'region' => $region,
                ]);
            }
        }
    }
}
