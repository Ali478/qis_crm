<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DynamicOptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $options = [
            // Service Types
            ['option_type' => 'service_type', 'value' => 'standard', 'label' => 'Standard', 'sort_order' => 1],
            ['option_type' => 'service_type', 'value' => 'express', 'label' => 'Express', 'sort_order' => 2],
            ['option_type' => 'service_type', 'value' => 'economy', 'label' => 'Economy', 'sort_order' => 3],
            ['option_type' => 'service_type', 'value' => 'priority', 'label' => 'Priority', 'sort_order' => 4],
            ['option_type' => 'service_type', 'value' => 'overnight', 'label' => 'Overnight', 'sort_order' => 5],
            
            // Transport Modes
            ['option_type' => 'transport_mode', 'value' => 'sea', 'label' => 'Sea Freight', 'sort_order' => 1],
            ['option_type' => 'transport_mode', 'value' => 'air', 'label' => 'Air Freight', 'sort_order' => 2],
            ['option_type' => 'transport_mode', 'value' => 'land', 'label' => 'Land Transport', 'sort_order' => 3],
            ['option_type' => 'transport_mode', 'value' => 'rail', 'label' => 'Rail', 'sort_order' => 4],
            ['option_type' => 'transport_mode', 'value' => 'multimodal', 'label' => 'Multimodal', 'sort_order' => 5],
            ['option_type' => 'transport_mode', 'value' => 'road', 'label' => 'Road', 'sort_order' => 6],
            
            // Shipment Types
            ['option_type' => 'shipment_type', 'value' => 'FCL', 'label' => 'FCL (Full Container Load)', 'sort_order' => 1],
            ['option_type' => 'shipment_type', 'value' => 'LCL', 'label' => 'LCL (Less Container Load)', 'sort_order' => 2],
            ['option_type' => 'shipment_type', 'value' => 'Air-freight', 'label' => 'Air Freight', 'sort_order' => 3],
            ['option_type' => 'shipment_type', 'value' => 'Bulk', 'label' => 'Bulk', 'sort_order' => 4],
            ['option_type' => 'shipment_type', 'value' => 'Break-bulk', 'label' => 'Break-bulk', 'sort_order' => 5],
            ['option_type' => 'shipment_type', 'value' => 'RoRo', 'label' => 'RoRo', 'sort_order' => 6],
            ['option_type' => 'shipment_type', 'value' => 'import', 'label' => 'Import', 'sort_order' => 7],
            ['option_type' => 'shipment_type', 'value' => 'export', 'label' => 'Export', 'sort_order' => 8],
            ['option_type' => 'shipment_type', 'value' => 'domestic', 'label' => 'Domestic', 'sort_order' => 9],
            
            // Weight Units
            ['option_type' => 'weight_unit', 'value' => 'kg', 'label' => 'Kilograms (kg)', 'sort_order' => 1],
            ['option_type' => 'weight_unit', 'value' => 'lb', 'label' => 'Pounds (lb)', 'sort_order' => 2],
            ['option_type' => 'weight_unit', 'value' => 'mt', 'label' => 'Metric Tons (MT)', 'sort_order' => 3],
            ['option_type' => 'weight_unit', 'value' => 'ton', 'label' => 'Tons', 'sort_order' => 4],
            
            // Volume Units
            ['option_type' => 'volume_unit', 'value' => 'm3', 'label' => 'Cubic Meters (m³)', 'sort_order' => 1],
            ['option_type' => 'volume_unit', 'value' => 'ft3', 'label' => 'Cubic Feet (ft³)', 'sort_order' => 2],
            ['option_type' => 'volume_unit', 'value' => 'l', 'label' => 'Liters (L)', 'sort_order' => 3],
            ['option_type' => 'volume_unit', 'value' => 'gal', 'label' => 'Gallons (gal)', 'sort_order' => 4],
        ];

        foreach ($options as $option) {
            DB::table('dynamic_options')->insert(array_merge($option, [
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
