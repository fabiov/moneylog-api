<?php
namespace App\Tests;

use App\Entity\Movement;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MovementsTest
 * @package App\Tests
 */
class MovementsTest extends AbstractTest
{
    // COLLECTION OPERATIONS// /////////////////////////////////////////////////////////////////////////////////////////

    public function testCreate(): void
    {
        // Mario tries to create a movement associated with Fabio's account
        $this->marioRequest(Request::METHOD_POST, '/api/movements', [
            'json' => [
                'date'          => '2020-05-20',
                'amount'        => '10.50',
                'description'   => 'Spesa',
                'account'       => '/api/accounts/1'
            ]
        ]);
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $this->fabioRequest(Request::METHOD_POST, '/api/movements', [
            'json' => [
                'date'          => '2020-05-20',
                'amount'        => '10.50',
                'description'   => 'Spesa',
                'account'       => '/api/accounts/1'
            ]
        ]);
        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonEquals([
            '@context'      => '/api/contexts/Movement',
            '@id'           => '/api/movements/5',
            '@type'         => 'Movement',
            'id'            => 5,
            'date'          => '2020-05-20T00:00:00+00:00',
            'amount'        => '10.50',
            'description'   => 'Spesa',
            'account'       => '/api/accounts/1'
        ]);
        self::assertMatchesResourceItemJsonSchema(Movement::class);
    }

    public function testGetCollection(): void
    {
        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        $this->marioRequest(Request::METHOD_GET, '/api/movements');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJsonEqualsFile(__DIR__ . '/json/movements_collection.json');
    }

    // Item operations /////////////////////////////////////////////////////////////////////////////////////////////////

    public function testGet(): void
    {
        // mario get his movement details
        $this->marioRequest(Request::METHOD_GET, '/api/movements/1');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJsonEquals('{
            "@context": "/api/contexts/Movement",
            "@id": "/api/movements/1",
            "@type": "Movement",
            "id": 1,
            "date": "2020-05-20T00:00:00+00:00",
            "amount": "10.00",
            "description": "Shopping",
            "account": "/api/accounts/3"
        }');

        // mario try to get giuseppe's movement details
        $this->marioRequest(Request::METHOD_GET, '/api/movements/4');
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testUpdate(): void
    {
        // Mario update his movement
        $this->marioRequest(Request::METHOD_PUT, '/api/movements/1', ['json' => ['description' => 'Diesel']]);
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJsonEquals('{
            "@context": "/api/contexts/Movement",
            "@id": "/api/movements/1",
            "@type": "Movement",
            "id": 1,
            "date": "2020-05-20T00:00:00+00:00",
            "amount": "10.00",
            "description": "Diesel",
            "account": "/api/accounts/3"
        }');

        // Mario update his movement
        $this->marioRequest(Request::METHOD_PUT, '/api/movements/4', ['json' => ['description' => 'x']]);
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);

        // Mario try to set giuseppe's account to his movement
        $this->marioRequest(Request::METHOD_PUT, '/api/movements/1', ['json' => ['account' => '/api/accounts/6']]);
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testDelete(): void
    {
        // Mario delete his movement
        $this->marioRequest(Request::METHOD_DELETE, '/api/movements/1');
        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        // Mario try to delete Giuseppe's movement
        $this->marioRequest(Request::METHOD_DELETE, '/api/movements/4');
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
