<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'company_name',    'value' => 'My Company',          'type' => 'string',  'group' => 'general', 'label' => 'Company Name'],
            ['key' => 'company_email',   'value' => 'info@mycompany.com',   'type' => 'string',  'group' => 'general', 'label' => 'Company Email'],
            ['key' => 'company_phone',   'value' => '+880-000-000000',       'type' => 'string',  'group' => 'general', 'label' => 'Company Phone'],
            ['key' => 'company_address', 'value' => '',                      'type' => 'string',  'group' => 'general', 'label' => 'Company Address'],

            // Meal
            ['key' => 'default_meal_rate',      'value' => '70',   'type' => 'integer', 'group' => 'meal', 'label' => 'Default Meal Rate'],
            ['key' => 'currency',               'value' => 'BDT',  'type' => 'string',  'group' => 'meal', 'label' => 'Currency Code'],
            ['key' => 'currency_symbol',        'value' => '৳',    'type' => 'string',  'group' => 'meal', 'label' => 'Currency Symbol'],
            ['key' => 'allow_guest_meals',      'value' => '1',    'type' => 'boolean', 'group' => 'meal', 'label' => 'Allow Guest Meals'],
            ['key' => 'allow_future_meals',     'value' => '0',    'type' => 'boolean', 'group' => 'meal', 'label' => 'Allow Future Meal Entries'],
            ['key' => 'max_meals_per_day',      'value' => '3',    'type' => 'integer', 'group' => 'meal', 'label' => 'Max Meals Per Day Per User'],

            // Email
            ['key' => 'send_daily_digest',      'value' => '1',    'type' => 'boolean', 'group' => 'email', 'label' => 'Send Daily Digest'],
            ['key' => 'send_monthly_report',    'value' => '1',    'type' => 'boolean', 'group' => 'email', 'label' => 'Send Monthly Report'],

            // System
            ['key' => 'maintenance_mode',       'value' => '0',    'type' => 'boolean', 'group' => 'system', 'label' => 'Maintenance Mode'],
            ['key' => 'items_per_page',         'value' => '15',   'type' => 'integer', 'group' => 'system', 'label' => 'Items Per Page'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
