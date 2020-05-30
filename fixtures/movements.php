<?php
return [
    App\Entity\Movement::class => [
        'movement_1_conto_mario'    => [
            'date'          => new DateTime('2020-05-20'),
            'amount'        => 10,
            'description'   => 'Shopping',
            'category'      => null,
            'account'       => '@account_mario_conto'
        ],
        'movement_1_contanti_mario' => [
            'date'          => new DateTime('2020-05-20'),
            'amount'        => 2,
            'description'   => 'Bar',
            'category'      => null,
            'account'       => '@account_mario_contanti'
        ],
        'movement_1_risparmi_mario' => [
            'date'          => new DateTime('2020-05-20'),
            'amount'        => 200,
            'description'   => 'Avanzo',
            'category'      => null,
            'account'       => '@account_mario_risparmi'
        ],
        'movement_1_conto_giuseppe' => [
            'date'          => new DateTime('2020-05-20'),
            'amount'        => 1500,
            'description'   => 'Stipendio',
            'category'      => null,
            'account'       => '@account_giuseppe_conto'
        ],
    ],
];
