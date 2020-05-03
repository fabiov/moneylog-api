<?php
namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Account;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AccountsTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    public function testCreate(): void
    {
        self::createClient()->request(Request::METHOD_POST, '/api/accounts', ['json' => [
            "name" => "Conto corrente",
            "recap" => true,
            "user" => "/api/users/1"
        ]]);

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonEquals('{
            "@context":"/api/contexts/Account",
            "@id":"/api/accounts/5",
            "@type":"Account",
            "id":5,
            "name":"Conto corrente",
            "recap":true,
            "user":"/api/users/1"
        }');
        self::assertMatchesResourceItemJsonSchema(Account::class);
    }

    public function testGet(): void
    {
        self::createClient()->request('GET', '/api/accounts/1');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJsonEquals('{
            "@context": "/api/contexts/Account",
            "@id": "/api/accounts/1",
            "@type": "Account",
            "id": 1,
            "name": "Conto corrente",
            "recap": true,
            "user": "/api/users/1"
        }');
    }

    public function testGetCollection(): void
    {
        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        self::createClient()->request(Request::METHOD_GET, '/api/accounts');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJsonEquals('{
            "@context": "/api/contexts/Account",
            "@id": "/api/accounts",
            "@type": "hydra:Collection",
            "hydra:member": [
                {"@id":"/api/accounts/1","@type":"Account","id":1,"name":"Conto corrente","recap":true,"user":"/api/users/1"},
                {"@id":"/api/accounts/2","@type":"Account","id":2,"name":"Contanti","recap":true,"user":"/api/users/1"},
                {"@id":"/api/accounts/3","@type":"Account","id":3,"name":"Conto deposito","recap":false,"user":"/api/users/1"},
                {"@id":"/api/accounts/4","@type":"Account","id":4,"name":"Conto corrente","recap":true,"user":"/api/users/2"}
            ],
            "hydra:totalItems": 4
        }');
    }

    public function testUpdate(): void
    {
        self::createClient()->request(Request::METHOD_PUT, '/api/accounts/1', ['json' => [
            'name' => 'Conto',
        ]]);
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJsonEquals([
            "@context" => "/api/contexts/Account",
            "@id" => "/api/accounts/1",
            "@type" => "Account",
            "id" => 1,
            "name" => "Conto",
            "recap" => true,
            "user" => "/api/users/1"
        ]);
    }

    public function testDelete(): void
    {
        static::createClient()->request(Request::METHOD_DELETE, '/api/accounts/1');
        self::assertResponseStatusCodeSame(Response::HTTP_METHOD_NOT_ALLOWED);
    }
}
