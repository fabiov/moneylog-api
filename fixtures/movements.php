<?php
return [
    App\Entity\Movement::class => [
        'movement_1_conto_mario'    => [
            'date'          => new DateTime(),
            'amount'        => 10,
            'description'   => 'Shopping',
            'account'       => '@account_mario_conto'
        ],
        'movement_1_contanti_mario' => [
            'date'          => new DateTime(),
            'amount'        => 2,
            'description'   => 'Bar',
            'account'       => '@account_mario_contanti'
        ],
        'movement_1_risparmi_mario' => [
            'date'          => new DateTime(),
            'amount'        => 200,
            'description'   => 'Avanzo',
            'account'       => '@account_mario_risparmi'
        ],
        'movement_1_conto_giuseppe' => [
            'date'          => new DateTime(),
            'amount'        => 1500,
            'description'   => 'Stipendio',
            'account'       => '@account_giuseppe_conto'
        ],
    ],
];
