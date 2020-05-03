<?php
namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    public function testCreate(): void
    {
        self::createClient()->request(Request::METHOD_POST, '/api/users', ['json' => [
            'email' => 'rasa.bianchi@test.it', 'name' => 'Rosa', 'surname' => 'Bianchi'
        ]]);

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonEquals('{
            "@context": "/api/contexts/User",
            "@id": "/api/users/3",
            "@type": "User",
            "id": 3,
            "email": "rasa.bianchi@test.it",
            "name": "Rosa",
            "surname": "Bianchi",
            "accounts": []
        }');
        self::assertMatchesResourceItemJsonSchema(User::class);
    }

    public function testGet(): void
    {
        self::createClient()->request('GET', '/api/users/1');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJsonEquals('{
            "@context": "/api/contexts/User",
            "@id": "/api/users/1",
            "@type": "User",
            "id": 1,
            "email": "mario.rossi@fixture.it",
            "name": "Mario",
            "surname": "Rossi",
            "accounts": ["/api/accounts/1","/api/accounts/2","/api/accounts/3"]
        }');
    }

    public function testGetCollection(): void
    {
        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        self::createClient()->request(Request::METHOD_GET, '/api/users');
        self::assertResponseStatusCodeSame(Response::HTTP_METHOD_NOT_ALLOWED);
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
