<?php
return [
    App\Entity\Account::class => [
        'account_mario_conto'    => ['name' => 'Conto corrente', 'recap' => true,  'user' => '@user_mario'],
        'account_mario_contanti' => ['name' => 'Contanti',       'recap' => true,  'user' => '@user_mario'],
        'account_mario_risparmi' => ['name' => 'Conto deposito', 'recap' => false, 'user' => '@user_mario'],
        'account_giuseppe_conto' => ['name' => 'Conto corrente', 'recap' => true,  'user' => '@user_giuseppe'],
    ],
];
