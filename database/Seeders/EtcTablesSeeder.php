<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ETC\Continent;
use App\Models\ETC\Country;
use App\Models\ETC\Currency;
use App\Models\ETC\District;
use App\Models\ETC\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

/**
 * Class EtcTablesSeeder
 */
class EtcTablesSeeder extends Seeder
{
    /**
     * @return string
     */
    public static function updateCurrencySymbols(): string
    {
        $path = base_path('database/Seeders/data/currency_symbols.csv');
        $csv  = Reader::from($path);

        $count    = 0;
        $affected = 0;
        foreach ($csv as $row) {
            if ($count++ == 0) {
                continue;
            }
            $alpha_code = trim($row[0]);
            $currency   = Currency::where('alpha_code', $alpha_code)->first();
            /**
             * @var Currency $currency
             */
            if (is_object($currency) and $currency->update(['symbol' => trim($row[2])])) $affected++;
        }

        return $affected." currencies updated.";
    }

    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            $path = base_path('database/Seeders/data/continent_codes.csv');
            $csv  = Reader::from($path);

            foreach ($csv as $row) {
                Continent::create([
                    'iso2' => strtoupper($row[0]),
                    'name' => ucwords($row[1]),
                ]);
            }
        });

        DB::transaction(function () {
            $path              = base_path('database/Seeders/data/continents_countries_currencies.csv');
            $csv               = Reader::from($path);

            $count = 0;
            foreach ($csv as $row) {
                if ($count++ == 0) {
                    continue;
                }

                Country::create([
                    'continent_id' => Continent::findByISO2($row[0])->id,
                    'name'         => $row[1],
                    'iso2'         => $row[2],
                    'iso3'         => ($iso3 = trim($row[3])),
                    'is_active'    => true,
                ]);
            }
        });

        DB::transaction(function () {
            $currencies       = [];
            $default_currency = Currency::DEFAULT;
            $path             = base_path('database/Seeders/data/continents_countries_currencies.csv');
            $csv              = Reader::from($path);

            $count = 0;
            foreach ($csv as $row) {
                if ($count++ == 0) {
                    continue;
                }
                $num_code = trim($row[7]);
                if (!empty($num_code)) {
                    $country = Country::findByISO2(trim($row[2]));

                    if (!key_exists($row[7], $currencies)) {
                        $currency              = Currency::create([
                            'num_code'   => $num_code,
                            'alpha_code' => ($code = trim($row[4])),
                            'minor_unit' => trim($row[5]),
                            'name'       => ucwords(trim($row[6])),
                            'is_active'  => ($code == $default_currency),
                        ]);
                        $currencies[$num_code] = $currency;
                    } else {
                        $currency = $currencies[$num_code];
                    }

                    //Associate countries and currencies
                    $country->currencies()->attach($currency->id);
                }
            }

            ///Seed crypto-currencies
            $path            = base_path('database/Seeders/data/crypto_currencies.csv');
            $csv             = Reader::from($path);

            $count = 0;
            foreach ($csv as $row) {
                if ($count++ == 0) {
                    continue;
                }
                /**
                 * @var Currency $currency
                 */
                $currency = Currency::create([
                    'num_code'   => trim($row[0]),
                    'alpha_code' => $code = trim($row[1]),
                    'minor_unit' => trim($row[2]),
                    'name'       => ucwords(trim($row[3])),
                    'is_active'  => true,
                    'is_crypto'  => true,
                ]);
                $currency->countries()->attach(Country::all());
            }
        });

        DB::transaction(function () {
            $countries = Country::all();

            /**
             * @var Country $country
             */
            foreach ($countries as $country) {
                $directory = base_path('database/Seeders/data/locations/' .strtolower($country->iso2));
                if (!is_dir($directory)) continue;

                $csv   = Reader::from($directory.'/regions-capitals.csv');
                $count = 0;
                foreach ($csv as $row) {
                    if ($count++ == 0) {
                        continue;
                    }

                    Region::create([
                        'country_id' => $country->id,
                        'name'       => $row[1],
                        'capital'    => $row[2],
                    ]);
                }

                $csv   = Reader::from($directory.'/regions-districts.csv');
                $count = 0;
                foreach ($csv as $row) {
                    if ($count++ == 0) {
                        continue;
                    }

                    District::create([
                        'name'      => $row[1],
                        'region_id' => Region::findByName($row[2])->id,
                    ]);
                }
            }
        });

        self::updateCurrencySymbols();
    }
}
