<?php
namespace App\Tests;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersTest extends AbstractTest
{
    public function testCreate(): void
    {
        self::createClient()->request(Request::METHOD_POST, '/api/users', ['json' => [
            'email' => 'rasa.bianchi@test.it', 'name' => 'Rosa', 'surname' => 'Bianchi'
        ]]);
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testGet(): void
    {
        $this->marioRequest('GET', '/api/users/1');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJsonEquals([
            '@context' => '/api/contexts/User',
            '@id'      => '/api/users/1',
            '@type'    => 'User',
            'id'       => 1,
            'email'    => 'fabio.ventura@fixture.it',
            'name'     => 'Fabio',
            'surname'  => 'Ventura',
        ]);
    }

    public function testGetCollection(): void
    {
        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        self::createClient()->request(Request::METHOD_GET, '/api/users');
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testUpdate(): void
    {
        self::createClient()->request(Request::METHOD_PUT, '/api/users/1', ['json' => [
            'name' => 'Rosa Bianca',
        ]]);
        self::assertResponseStatusCodeSame(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public function testDelete(): void
    {
        self::createClient()->request(Request::METHOD_DELETE, '/api/users/1');
        self::assertResponseStatusCodeSame(Response::HTTP_METHOD_NOT_ALLOWED);
    }

//    public function testLogin(): void
//    {
//        $response = static::createClient()->request('POST', '/login', ['json' => [
//            'email' => 'admin@example.com',
//            'password' => 'admin',
//        ]]);
//        $this->assertResponseIsSuccessfull();
//    }
}
