<?php
return [
    App\Entity\Setting::class => [
        'setting_fabio'    => ['user' => '@user_fabio',    'payday' => 12, 'months' => 18, 'provisioning' => true],
        'setting_mario'    => ['user' => '@user_mario',    'payday' => 27, 'months' => 24, 'provisioning' => false],
        'setting_giuseppe' => ['user' => '@user_giuseppe', 'payday' => 15, 'months' => 12, 'provisioning' => false],
    ]
];
