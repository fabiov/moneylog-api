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
    // COLLECTION OPERATIONS// ///////////////////////////////////////////////////////////////////////////////////////////

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
    }

    public function testUpdate(): void
    {
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
    }

    public function testDelete(): void
    {
        $this->marioRequest(Request::METHOD_DELETE, '/api/movements/1');
        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
