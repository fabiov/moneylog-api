<?php
namespace App\Tests;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegistrationTest extends AbstractTest
{
    public function testNewUser(): void
    {
        self::createClient()->request(Request::METHOD_POST, '/registration/new-user', ['json' => [
            'name'     => 'Rosa',
            'surname'  => 'Bianchi',
            'email'    => 'rasa.bianchi@test.it',
            'password' => 'Rasa1234',
        ]]);
        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        // try to create the same user
        self::createClient()->request(Request::METHOD_POST, '/registration/new-user', ['json' => [
            'name'     => 'Mario',
            'surname'  => 'Rossi',
            'email'    => 'mario.rossi@fixture.it',
            'password' => 'Mario123',
        ]]);
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
}
