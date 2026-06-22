<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Tax\Models\TaxRate;
use Webkul\Tax\Models\TaxCategory;
use Webkul\Core\Models\CountryState;

class GSTTaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $homeState = 'MH'; // Maharashtra
        $countryCode = 'IN';

        $gstRates = [
            5 => 2.5,
            12 => 6,
            18 => 9,
            28 => 14
        ];

        $states = CountryState::where('country_code', $countryCode)->get();

        foreach ($gstRates as $totalGst => $splitGst) {
            $categoryName = "GST {$totalGst}%";
            $categoryCode = "gst_{$totalGst}";

            $taxCategory = TaxCategory::firstOrCreate([
                'code' => $categoryCode
            ], [
                'name' => $categoryName,
                'description' => $categoryName
            ]);

            // Create CGST for Home State
            $cgstIdentifier = "CGST {$splitGst}%";
            $cgst = TaxRate::firstOrCreate([
                'identifier' => $cgstIdentifier,
                'state'      => $homeState,
                'country'    => $countryCode
            ], [
                'is_zip'   => 0,
                'zip_code' => '',
                'tax_rate' => $splitGst
            ]);

            // Create SGST for Home State
            $sgstIdentifier = "SGST {$splitGst}%";
            $sgst = TaxRate::firstOrCreate([
                'identifier' => $sgstIdentifier,
                'state'      => $homeState,
                'country'    => $countryCode
            ], [
                'is_zip'   => 0,
                'zip_code' => '',
                'tax_rate' => $splitGst
            ]);

            $rateIds = [$cgst->id, $sgst->id];

            // Create IGST for all other states
            foreach ($states as $state) {
                if ($state->code === $homeState) {
                    continue;
                }

                $igstIdentifier = "IGST {$totalGst}% ({$state->code})";
                $igst = TaxRate::firstOrCreate([
                    'identifier' => $igstIdentifier,
                    'state'      => $state->code,
                    'country'    => $countryCode
                ], [
                    'is_zip'   => 0,
                    'zip_code' => '',
                    'tax_rate' => $totalGst
                ]);

                $rateIds[] = $igst->id;
            }

            // Sync the tax rates to the category
            $taxCategory->tax_rates()->syncWithoutDetaching($rateIds);
            
            $this->command->info("Created and linked tax rates for {$categoryName}");
        }
    }
}
