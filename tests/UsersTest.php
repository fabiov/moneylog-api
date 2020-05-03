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
        $response = static::createClient()->request(Request::METHOD_POST, '/api/users', ['json' => [
            'email' => 'rasa.bianchi@test.it', 'name' => 'Rosa', 'surname' => 'Bianchi'
        ]]);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        static::assertJsonStringEqualsJsonString('{
            "@context": "/api/contexts/User",
            "@id": "/api/users/3",
            "@type": "User",
            "id": 3,
            "email": "rasa.bianchi@test.it",
            "name": "Rosa",
            "surname": "Bianchi",
            "accounts": []
        }', $response->getContent());

        $this->assertMatchesResourceItemJsonSchema(User::class);
    }

    public function testGet(): void
    {
        $response = static::createClient()->request('GET', '/api/users/1');
        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        echo $response->getContent();
        self::assertJsonStringEqualsJsonString('{
            "@context": "/api/contexts/User",
            "@id": "/api/users/1",
            "@type": "User",
            "id": 1,
            "email": "mario.rossi@fixture.it",
            "name": "Mario",
            "surname": "Rossi",
            "accounts": ["/api/accounts/1","/api/accounts/2","/api/accounts/3"]
        }', $response->getContent());
    }

    public function testGetCollection(): void
    {
        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        static::createClient()->request(Request::METHOD_GET, '/api/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public function testUpdate(): void
    {
        static::createClient()->request(Request::METHOD_PUT, '/api/users/1', ['json' => [
            'name' => 'Rosa Bianca',
        ]]);
        self::assertResponseStatusCodeSame(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public function testDelete(): void
    {
        $client = static::createClient()->request(Request::METHOD_DELETE, '/api/users/1');
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
