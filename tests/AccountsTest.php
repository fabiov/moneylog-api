<?php
namespace App\Tests;

use App\Entity\Account;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AccountsTest
 * @package App\Tests
 */
class AccountsTest extends AbstractTest
{
    // COLLECTION OPERATIONS ///////////////////////////////////////////////////////////////////////////////////////////

    public function testCreate(): void
    {
        // Mario create a new account with Fabio's user id,
        // but user id is ignored and account is created with Mario's user id
        $this->marioRequest(Request::METHOD_POST, '/api/accounts', [
            'json' => ['name' => 'Conto corrente', 'recap' => true, 'user' => '/api/users/1']
        ]);
        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonEquals('{
            "@context":"/api/contexts/Account",
            "@id":"/api/accounts/7",
            "@type":"Account",
            "id":7,
            "name":"Conto corrente",
            "recap":true
        }');
        self::assertMatchesResourceItemJsonSchema(Account::class);
    }

    public function testGetCollection(): void
    {
        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        $this->marioRequest(Request::METHOD_GET, '/api/accounts');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJsonEquals('{
            "@context": "/api/contexts/Account",
            "@id": "/api/accounts",
            "@type": "hydra:Collection",
            "hydra:member": [
                {
                    "@id": "/api/accounts/3",
                    "@type": "Account",
                    "id": 3,
                    "name": "Conto corrente",
                    "recap": true
                },
                {
                    "@id": "/api/accounts/4",
                    "@type": "Account",
                    "id": 4,
                    "name": "Contanti",
                    "recap": true
                },
                {
                    "@id": "/api/accounts/5",
                    "@type": "Account",
                    "id": 5,
                    "name": "Conto deposito",
                    "recap": false
                }
            ],
            "hydra:totalItems": 3
        }');
    }

    // ITEM OPERATIONS /////////////////////////////////////////////////////////////////////////////////////////////////

    public function testGetItem(): void
    {
        // Mario try to view a Fabio's account data
        $this->marioRequest(Request::METHOD_GET, '/api/accounts/1');
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);

        // Fabio view his account data
        $this->fabioRequest(Request::METHOD_GET, '/api/accounts/1');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJsonEquals('{
            "@context": "/api/contexts/Account",
            "@id": "/api/accounts/1",
            "@type": "Account",
            "id": 1,
            "name": "Banco Popolare",
            "recap": true
        }');
    }

    public function testUpdate(): void
    {
        // Mario tries to modify a Fabio's account
        $this->marioRequest(Request::METHOD_PUT, '/api/accounts/1', ['json' => ['name' => 'Conto']]);
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);

        // Fabio modify his account
        $this->fabioRequest(Request::METHOD_PUT, '/api/accounts/1', ['json' => ['name' => 'Conto']]);
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJsonEquals('{
            "@context": "/api/contexts/Account",
            "@id": "/api/accounts/1",
            "@type": "Account",
            "id": 1,
            "name": "Conto",
            "recap": true
        }');
    }

    public function testDelete(): void
    {
        $this->marioRequest(Request::METHOD_DELETE, '/api/accounts/1');
        self::assertResponseStatusCodeSame(Response::HTTP_METHOD_NOT_ALLOWED);
    }
}
