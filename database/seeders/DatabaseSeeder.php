<?php

namespace Database\Seeders;

use App\Models\Buyer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'System Administrator',
            'username' => 'admin',
            'email' => 'admin@agriculture.com',
            'password' => 'password',
            'role' => 'admin',
        ]);

        // Create sales officer
        User::create([
            'name' => 'Sales Officer',
            'username' => 'sales',
            'email' => 'sales@agriculture.com',
            'password' => 'password',
            'role' => 'sales_officer',
        ]);

        // Sample products
        $products = [
            ['name' => 'Organic Rice (1kg)', 'quantity' => 200, 'buying_price' => 1.50, 'selling_price' => 2.50],
            ['name' => 'Maize Flour (2kg)', 'quantity' => 150, 'buying_price' => 2.00, 'selling_price' => 3.50],
            ['name' => 'Fresh Tomatoes (kg)', 'quantity' => 100, 'buying_price' => 0.80, 'selling_price' => 1.50],
            ['name' => 'Irish Potatoes (kg)', 'quantity' => 300, 'buying_price' => 0.50, 'selling_price' => 1.00],
            ['name' => 'Sweet Potatoes (kg)', 'quantity' => 250, 'buying_price' => 0.40, 'selling_price' => 0.80],
            ['name' => 'Bananas (bunch)', 'quantity' => 80, 'buying_price' => 2.00, 'selling_price' => 3.00],
            ['name' => 'Cabbage (head)', 'quantity' => 60, 'buying_price' => 0.60, 'selling_price' => 1.20],
            ['name' => 'Carrots (kg)', 'quantity' => 120, 'buying_price' => 0.70, 'selling_price' => 1.30],
            ['name' => 'Onions (kg)', 'quantity' => 90, 'buying_price' => 0.90, 'selling_price' => 1.60],
            ['name' => 'Beans (kg)', 'quantity' => 180, 'buying_price' => 1.20, 'selling_price' => 2.00],
            ['name' => 'Fresh Milk (liter)', 'quantity' => 50, 'buying_price' => 0.70, 'selling_price' => 1.10],
            ['name' => 'Honey (500ml)', 'quantity' => 40, 'buying_price' => 4.00, 'selling_price' => 6.00],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        // Sample buyers
        $buyers = [
            ['name' => 'Jean Pierre Habimana', 'phone' => '+250 788 100 200', 'email' => 'jp@example.com', 'address' => 'Kigali, Rwanda'],
            ['name' => 'Alice Muhawenimana', 'phone' => '+250 788 300 400', 'email' => 'alice@example.com', 'address' => 'Musanze, Rwanda'],
            ['name' => 'David Kagame', 'phone' => '+250 722 500 600', 'email' => 'david@example.com', 'address' => 'Huye, Rwanda'],
            ['name' => 'Grace Uwimana', 'phone' => '+250 733 700 800', 'email' => 'grace@example.com', 'address' => 'Nyagatare, Rwanda'],
            ['name' => 'Patrick Niyonzima', 'phone' => '+250 788 900 000', 'email' => 'patrick@example.com', 'address' => 'Rubavu, Rwanda'],
        ];

        foreach ($buyers as $buyer) {
            Buyer::create($buyer);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin login: username "admin" / password (Select "Admin" on login page)');
        $this->command->info('Sales Officer login: email "sales@agriculture.com" / password (Select "Sales Officer" on login page)');
    }
}
