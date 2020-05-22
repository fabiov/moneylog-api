<?php
return [
    App\Entity\User::class => [
        'user_fabio'    => [
            'email'    => 'fabio.ventura@fixture.it',
            'name'     => 'Fabio',
            'surname'  => 'Ventura',
            'password' => '\$argon2i\$v=19\$m=65536,t=4,p=1\$QlphZUhlNHB3RzQ3SXRMaQ\$0+ZRMzazg3SmTw11dJQ9y0o+FSQ8YB6PMr0F20wV7x4' // Fabio123
        ],
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
