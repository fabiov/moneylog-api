<?php
namespace App\Tests;

use App\Entity\Provision;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ProvisionsTest
 * @package App\Tests
 */
class ProvisionsTest extends AbstractTest
{
    // COLLECTION OPERATIONS ///////////////////////////////////////////////////////////////////////////////////////////

    public function testCreate(): void
    {
        // Mario create a new provision with Fabio's user id,
        // but user id is ignored and provision is created with Mario's user id
        $this->marioRequest(Request::METHOD_POST, '/api/provisions', ['json' => [
            'date' => '2020-05-29', 'amount' => '150', 'description' => 'Provision', 'user' => '/api/users/1'
        ]]);

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonEquals('{
            "@context":"/api/contexts/Provision",
            "@id":"/api/provisions/5",
            "@type":"Provision",
            "id":5,
            "date":"2020-05-29T00:00:00+00:00",
            "amount":"150.00",
            "description":"Provision"
        }');
        self::assertMatchesResourceItemJsonSchema(Provision::class);
    }

    public function testGetCollection(): void
    {
        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        $this->marioRequest(Request::METHOD_GET, '/api/provisions');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJsonEquals('{
            "@context": "/api/contexts/Provision",
            "@id": "/api/provisions",
            "@type": "hydra:Collection",
            "hydra:member": [
                {
                    "@id": "/api/provisions/2",
                    "@type": "Provision",
                    "id": 2,
                    "date": "2020-05-20T00:00:00+00:00",
                    "amount": "2.00",
                    "description": "Bar"
                },
                {
                    "@id": "/api/provisions/3",
                    "@type": "Provision",
                    "id": 3,
                    "date": "2020-05-20T00:00:00+00:00",
                    "amount": "200.00",
                    "description": "Avanzo"
                }
            ],
            "hydra:totalItems": 2
        }');
    }

    // ITEM OPERATIONS /////////////////////////////////////////////////////////////////////////////////////////////////

    public function testGetItem(): void
    {
        // Mario try to view a Fabio's provision data
        $this->marioRequest(Request::METHOD_GET, '/api/provisions/1');
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);

        // Fabio view his provision data
        $this->fabioRequest(Request::METHOD_GET, '/api/provisions/1');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJsonEquals('{
            "@context": "/api/contexts/Provision",
            "@id": "/api/provisions/1",
            "@type": "Provision",
            "id": 1,
            "date": "2020-05-20T00:00:00+00:00",
            "amount": "10.00",
            "description": "Provision 1 2020-05"
        }');
    }

    public function testUpdate(): void
    {
        // Mario tries to modify a Fabio's provision
        $this->marioRequest(Request::METHOD_PUT, '/api/provisions/1', ['json' => [
            'description' => 'Modified'
        ]]);
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);

        // Fabio modify his provision
        $this->fabioRequest(Request::METHOD_PUT, '/api/provisions/1', ['json' => [
            'description' => 'Modified'
        ]]);
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJsonEquals('{
            "@context": "/api/contexts/Provision",
            "@id": "/api/provisions/1",
            "@type": "Provision",
            "id": 1,
            "date": "2020-05-20T00:00:00+00:00",
            "amount": "10.00",
            "description": "Modified"
        }');
    }

    public function testDelete(): void
    {
        // Mario try to delete Fabio's provision
        $this->marioRequest(Request::METHOD_DELETE, '/api/provisions/1');
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);

        // Mario try to delete Fabio's provision
        $this->fabioRequest(Request::METHOD_DELETE, '/api/provisions/1');
        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
