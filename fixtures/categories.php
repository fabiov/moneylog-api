<?php
return [
    App\Entity\Category::class => [
        'category_home_mario'      => ['name' => 'Home',      'enabled' => true,  'user' => '@user_mario'],
        'category_car_giuseppe'    => ['name' => 'Car',       'enabled' => true,  'user' => '@user_giuseppe'],
        'category_home_fabio'      => ['name' => 'Home',      'enabled' => true,  'user' => '@user_fabio'],
        'category_car_fabio'       => ['name' => 'Car',       'enabled' => true,  'user' => '@user_fabio'],
        'category_motorbike_fabio' => ['name' => 'Motorbike', 'enabled' => false, 'user' => '@user_fabio'],
    ],
];
