<?php
return [
    App\Entity\Provision::class => [
        'provision_1_fabio'    => [
            'user'        => '@user_fabio',
            'date'        => new DateTime('2020-05-20'),
            'amount'      => 10,
            'description' => 'Shopping',
        ],
        'provision_2_mario'    => [
            'user'        => '@user_mario',
            'date'        => new DateTime('2020-05-20'),
            'amount'      => 2,
            'description' => 'Bar',
        ],
        'provision_3_mario'    => [
            'user'        => '@user_mario',
            'date'        => new DateTime('2020-05-20'),
            'amount'      => 200,
            'description' => 'Avanzo',
        ],
        'provision_4_giuseppe' => [
            'user'        => '@user_giuseppe',
            'date'        => new DateTime('2020-05-20'),
            'amount'      => 1500,
            'description' => 'Stipendio',
        ],
    ],
];
