<?php
namespace App\Tests;

use App\Entity\Account;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Class AccountsTest
 * @package App\Tests
 */
class AccountsTest extends AbstractTest
{
    public function testCreate(): void
    {
        $this->authRequest(Request::METHOD_POST, '/api/accounts', [
            'json'    => ["name" => 'Conto corrente', "recap" => true, "user" => "/api/users/1"]
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonEquals('{
            "@context":"/api/contexts/Account",
            "@id":"/api/accounts/7",
            "@type":"Account",
            "id":7,
            "name":"Conto corrente",
            "recap":true,
            "user":"/api/users/1"
        }');
        self::assertMatchesResourceItemJsonSchema(Account::class);
    }

    public function testGet(): void
    {
        $this->authRequest(Request::METHOD_GET, '/api/accounts/1');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJsonEquals('{
            "@context": "/api/contexts/Account",
            "@id": "/api/accounts/1",
            "@type": "Account",
            "id": 1,
            "name": "Banco Popolare",
            "recap": true,
            "user": "/api/users/1"
        }');
    }

    public function testGetCollection(): void
    {
        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        $this->authRequest(Request::METHOD_GET, '/api/accounts');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJsonEquals('{
            "@context": "/api/contexts/Account",
            "@id": "/api/accounts",
            "@type": "hydra:Collection",
            "hydra:member": [
                {
                    "@id": "/api/accounts/1",
                    "@type": "Account",
                    "id": 1,
                    "name": "Banco Popolare",
                    "recap": true,
                    "user": "/api/users/1"
                },
                {
                    "@id": "/api/accounts/2",
                    "@type": "Account",
                    "id": 2,
                    "name": "Conto Deposito",
                    "recap": false,
                    "user": "/api/users/1"
                },
                {
                    "@id": "/api/accounts/3",
                    "@type": "Account",
                    "id": 3,
                    "name": "Conto corrente",
                    "recap": true,
                    "user": "/api/users/2"
                },
                {
                    "@id": "/api/accounts/4",
                    "@type": "Account",
                    "id": 4,
                    "name": "Contanti",
                    "recap": true,
                    "user": "/api/users/2"
                },
                {
                    "@id": "/api/accounts/5",
                    "@type": "Account",
                    "id": 5,
                    "name": "Conto deposito",
                    "recap": false,
                    "user": "/api/users/2"
                },
                {
                    "@id": "/api/accounts/6",
                    "@type": "Account",
                    "id": 6,
                    "name": "Conto corrente",
                    "recap": true,
                    "user": "/api/users/3"
                }
            ],
            "hydra:totalItems": 6
        }');
    }

    public function testUpdate(): void
    {
        $this->authRequest(Request::METHOD_PUT, '/api/accounts/1', ['json' => ['name' => 'Conto']]);
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
        $this->authRequest(Request::METHOD_DELETE, '/api/accounts/1');
        self::assertResponseStatusCodeSame(Response::HTTP_METHOD_NOT_ALLOWED);
    }
}
