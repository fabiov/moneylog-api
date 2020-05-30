<?php
return [
    App\Entity\Account::class => [
        'account_fabio_banco_popolare' => ['name' => 'Banco Popolare', 'recap' => true,  'user' => '@user_fabio'],
        'account_fabio_conto_deposito' => ['name' => 'Conto Deposito', 'recap' => false, 'user' => '@user_fabio'],
        'account_mario_conto'          => ['name' => 'Conto corrente', 'recap' => true,  'user' => '@user_mario'],
        'account_mario_contanti'       => ['name' => 'Contanti',       'recap' => true,  'user' => '@user_mario'],
        'account_mario_risparmi'       => ['name' => 'Conto deposito', 'recap' => false, 'user' => '@user_mario'],
        'account_giuseppe_conto'       => ['name' => 'Conto corrente', 'recap' => true,  'user' => '@user_giuseppe'],
    ],
];
