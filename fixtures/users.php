<?php
return [
    App\Entity\User::class => [
        'user_mario'    => [
            'email'    => 'mario.rossi@fixture.it',
            'name'     => 'Mario',
            'surname'  => 'Rossi',
            'password' => '\$argon2i\$v=19\$m=65536,t=4,p=1\$UzZKZ0ZydlhPQksxcE5qeQ\$qLkSEJzo4TUx5w6UOWifUBRJq55WDQ6l7Z4vu+KcPPU' // mario123
        ],
        'user_giuseppe' => [
            'email'    => 'giuseppe.verdi@fixture.it',
            'name'     => 'giuseppe',
            'surname'  => 'Verdi',
            'password' => '\$argon2i\$v=19\$m=65536,t=4,p=1\$ajJMMnNkMmhJSDQ4NEdxLw\$Pu+4oDUcrTZMerlZSux1fbAi6VPErALs3JVdUzFEwzI', // giuseppe
        ],
    ],
];
