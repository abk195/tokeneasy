<?php

use Illuminate\Database\Seeder;
use App\Property;
use App\PropertyImage;
use App\User;
use App\UserContract;
use App\BlockchainModel;

class DemoPropertiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get or create a demo user
        $user = User::firstOrCreate(
            ['email' => 'demo@tokenize.ai'],
            [
                'name' => 'Demo Issuer',
                'password' => bcrypt('password'),
                'user_type' => 2, // Issuer
                'email_verified_at' => now(),
            ]
        );

        // Get or create a blockchain
        $blockchain = BlockchainModel::firstOrCreate(
            ['blockchain_name' => 'Ethereum'],
            [
                'abbreviation' => 'ETH',
                'link' => 'https://etherscan.io/',
                'chain_id' => 1,
            ]
        );

        // Demo properties data
        $properties = [
            [
                'propertyName' => 'Downtown Office Tower',
                'propertyLocation' => 'New York, NY',
                'propertyType' => 'Office',
                'totalDealSize' => 50000000,
                'dividend' => 8.5,
                'expectedIrr' => 12.5,
                'initialInvestment' => 5000,
                'propertyEquityMultiple' => 2.1,
                'holdingPeriod' => '5 years',
                'total_sft' => 150000,
                'propertyOverview' => 'Premium Class A office building in the heart of Manhattan with long-term tenant leases.',
                'propertyHighlights' => 'Prime location, stable cash flow, professional management',
                'locality' => 'Manhattan',
                'yearOfConstruction' => 2018,
                'storeys' => 25,
                'status' => 'active',
                'token_type' => 1,
            ],
            [
                'propertyName' => 'Luxury Apartment Complex',
                'propertyLocation' => 'Miami, FL',
                'propertyType' => 'Residential',
                'totalDealSize' => 35000000,
                'dividend' => 7.2,
                'expectedIrr' => 11.8,
                'initialInvestment' => 3000,
                'propertyEquityMultiple' => 1.9,
                'holdingPeriod' => '4 years',
                'total_sft' => 120000,
                'propertyOverview' => 'Modern luxury apartment complex with premium amenities and ocean views.',
                'propertyHighlights' => 'High-end amenities, strong rental demand, waterfront location',
                'locality' => 'South Beach',
                'yearOfConstruction' => 2020,
                'storeys' => 18,
                'status' => 'active',
                'token_type' => 1,
            ],
            [
                'propertyName' => 'Industrial Warehouse Portfolio',
                'propertyLocation' => 'Dallas, TX',
                'propertyType' => 'Industrial',
                'totalDealSize' => 28000000,
                'dividend' => 9.1,
                'expectedIrr' => 13.2,
                'initialInvestment' => 2500,
                'propertyEquityMultiple' => 2.3,
                'holdingPeriod' => '6 years',
                'total_sft' => 200000,
                'propertyOverview' => 'Multi-tenant industrial warehouse portfolio serving the growing logistics sector.',
                'propertyHighlights' => 'E-commerce growth, strategic location, diversified tenant base',
                'locality' => 'Dallas-Fort Worth',
                'yearOfConstruction' => 2019,
                'storeys' => 1,
                'status' => 'active',
                'token_type' => 2,
            ],
            [
                'propertyName' => 'Retail Shopping Center',
                'propertyLocation' => 'Austin, TX',
                'propertyType' => 'Retail',
                'totalDealSize' => 22000000,
                'dividend' => 6.8,
                'expectedIrr' => 10.5,
                'initialInvestment' => 2000,
                'propertyEquityMultiple' => 1.7,
                'holdingPeriod' => '5 years',
                'total_sft' => 85000,
                'propertyOverview' => 'Well-anchored retail shopping center with national and local tenants.',
                'propertyHighlights' => 'Strong anchor tenants, high foot traffic, stable income',
                'locality' => 'Austin Metro',
                'yearOfConstruction' => 2017,
                'storeys' => 2,
                'status' => 'active',
                'token_type' => 1,
            ],
            [
                'propertyName' => 'Medical Office Building',
                'propertyLocation' => 'Phoenix, AZ',
                'propertyType' => 'Medical',
                'totalDealSize' => 18000000,
                'dividend' => 7.8,
                'expectedIrr' => 11.2,
                'initialInvestment' => 4000,
                'propertyEquityMultiple' => 1.8,
                'holdingPeriod' => '7 years',
                'total_sft' => 65000,
                'propertyOverview' => 'Modern medical office building with long-term healthcare tenant leases.',
                'propertyHighlights' => 'Healthcare sector growth, stable tenants, recession-resistant',
                'locality' => 'Phoenix Metro',
                'yearOfConstruction' => 2021,
                'storeys' => 4,
                'status' => 'active',
                'token_type' => 1,
            ],
            [
                'propertyName' => 'Hotel & Conference Center',
                'propertyLocation' => 'Nashville, TN',
                'propertyType' => 'Hospitality',
                'totalDealSize' => 42000000,
                'dividend' => 5.5,
                'expectedIrr' => 14.2,
                'initialInvestment' => 6000,
                'propertyEquityMultiple' => 2.5,
                'holdingPeriod' => '8 years',
                'total_sft' => 180000,
                'propertyOverview' => 'Full-service hotel with conference facilities in a growing business district.',
                'propertyHighlights' => 'Business travel recovery, conference facilities, prime location',
                'locality' => 'Nashville Downtown',
                'yearOfConstruction' => 2019,
                'storeys' => 12,
                'status' => 'active',
                'token_type' => 2,
            ],
        ];

        foreach ($properties as $propertyData) {
            // Create property
            $property = Property::create(array_merge($propertyData, [
                'user_id' => $user->id,
                'blockchain_id' => $blockchain->id,
            ]));

            // Create user contract for the property
            $userContract = UserContract::create([
                'user_id' => $user->id,
                'property_id' => $property->id,
                'tokenname' => $property->propertyName . ' Token',
                'tokensymbol' => strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $property->propertyName), 0, 4)),
                'contract_address' => '0x' . strtoupper(substr(md5($property->id . time()), 0, 40)),
                'tokensupply' => 1000000,
                'tokenbalance' => rand(200000, 800000), // Random sold tokens
                'tokenvalue' => $property->totalDealSize / 1000000, // Token value based on deal size
                'coin' => 'ETH',
            ]);

            // Create a sample property image
            PropertyImage::create([
                'property_id' => $property->id,
                'image_path' => 'demo/property-' . $property->id . '.jpg',
                'image_type' => 'main',
            ]);
        }

        echo "Demo properties created successfully!\n";
    }
}