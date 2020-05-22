<?php
namespace App\Tests;

use App\Entity\Account;
use App\Entity\Movement;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Class MovementsTest
 * @package App\Tests
 */
class MovementsTest extends AbstractTest
{
    public function testCreate(): void
    {
        // Collection operations ///////////////////////////////////////////////////////////////////////////////////////
        $this->authRequest(Request::METHOD_POST, '/api/movements', [
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
        $this->authRequest(Request::METHOD_GET, '/api/movements');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJsonEquals('{
            "@context": "/api/contexts/Movement",
            "@id": "/api/movements",
            "@type": "hydra:Collection",
            "hydra:member": [
                {
                    "@id": "/api/movements/1",
                    "@type": "Movement",
                    "id": 1,
                    "date": "2020-05-20T00:00:00+00:00",
                    "amount": "10.00",
                    "description": "Shopping",
                    "account": "/api/accounts/1"
                },
                {
                    "@id": "/api/movements/2",
                    "@type": "Movement",
                    "id": 2,
                    "date": "2020-05-20T00:00:00+00:00",
                    "amount": "2.00",
                    "description": "Bar",
                    "account": "/api/accounts/2"
                },
                {
                    "@id": "/api/movements/3",
                    "@type": "Movement",
                    "id": 3,
                    "date": "2020-05-20T00:00:00+00:00",
                    "amount": "200.00",
                    "description": "Avanzo",
                    "account": "/api/accounts/3"
                },
                {
                    "@id": "/api/movements/4",
                    "@type": "Movement",
                    "id": 4,
                    "date": "2020-05-20T00:00:00+00:00",
                    "amount": "1500.00",
                    "description": "Stipendio",
                    "account": "/api/accounts/4"
                }
            ],
            "hydra:totalItems": 4,
            "hydra:search": {
                "@type": "hydra:IriTemplate",
                "hydra:template": "/api/movements{?date[before],date[strictly_before],date[after],date[strictly_after]}",
                "hydra:variableRepresentation": "BasicRepresentation",
                "hydra:mapping": [
                    {
                        "@type": "IriTemplateMapping",
                        "variable": "date[before]",
                        "property": "date",
                        "required": false
                    },
                    {
                        "@type": "IriTemplateMapping",
                        "variable": "date[strictly_before]",
                        "property": "date",
                        "required": false
                    },
                    {
                        "@type": "IriTemplateMapping",
                        "variable": "date[after]",
                        "property": "date",
                        "required": false
                    },
                    {"@type":"IriTemplateMapping","variable":"date[strictly_after]","property":"date","required":false}
                ]
            }
        }');
    }

    // Item operations /////////////////////////////////////////////////////////////////////////////////////////////////

    public function testGet(): void
    {
        $this->authRequest(Request::METHOD_GET, '/api/movements/1');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJsonEquals('{
            "@context": "/api/contexts/Movement",
            "@id": "/api/movements/1",
            "@type": "Movement",
            "id": 1,
            "date": "2020-05-20T00:00:00+00:00",
            "amount": "10.00",
            "description": "Shopping",
            "account": "/api/accounts/1"
        }');
    }

    public function testUpdate(): void
    {
        $this->authRequest(Request::METHOD_PUT, '/api/movements/1', ['json' => ['description' => 'Diesel']]);
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJsonEquals('{
            "@context": "/api/contexts/Movement",
            "@id": "/api/movements/1",
            "@type": "Movement",
            "id": 1,
            "date": "2020-05-20T00:00:00+00:00",
            "amount": "10.00",
            "description": "Diesel",
            "account": "/api/accounts/1"
        }');
    }

    public function testDelete(): void
    {
        $this->authRequest(Request::METHOD_DELETE, '/api/movements/1');
        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
