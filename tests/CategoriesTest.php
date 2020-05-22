<?php
namespace App\Tests;

use App\Entity\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CategoriesTest
 * @package App\Tests
 */
class CategoriesTest extends AbstractTest
{
    // COLLECTION OPERATIONS ///////////////////////////////////////////////////////////////////////////////////////////

   public function testCreate(): void
    {
        $this->authRequest(Request::METHOD_POST, '/api/categories', ['json' => [
            'name' => 'Car', 'enabled' => true, 'user' => '/api/users/1'
        ]]);

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonEquals([
            '@context' => '/api/contexts/Category',
            '@id'      => '/api/categories/6',
            '@type'    => 'Category',
            'id'       => 6,
            'name'     => 'Car',
            'enabled'  => true,
            'user'     => '/api/users/1'
        ]);
        self::assertMatchesResourceItemJsonSchema(Category::class);

        self::createClient()->request(Request::METHOD_POST, '/api/categories');
        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testGetCollection(): void
    {
        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        $this->authRequest(Request::METHOD_GET, '/api/categories');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJsonEquals('{
            "@context": "/api/contexts/Category",
            "@id": "/api/categories",
            "@type": "hydra:Collection",
            "hydra:member": [
                {
                    "@id": "/api/categories/1",
                    "@type": "Category",
                    "id": 1,
                    "name": "Home",
                    "enabled": true,
                    "user": "/api/users/2"
                },
                {
                    "@id": "/api/categories/2",
                    "@type": "Category",
                    "id": 2,
                    "name": "Car",
                    "enabled": true,
                    "user": "/api/users/3"
                },
                {
                    "@id": "/api/categories/3",
                    "@type": "Category",
                    "id": 3,
                    "name": "Home",
                    "enabled": true,
                    "user": "/api/users/1"
                },
                {
                    "@id": "/api/categories/4",
                    "@type": "Category",
                    "id": 4,
                    "name": "Car",
                    "enabled": true,
                    "user": "/api/users/1"
                },
                {
                    "@id": "/api/categories/5",
                    "@type": "Category",
                    "id": 5,
                    "name": "Motorbike",
                    "enabled": false,
                    "user": "/api/users/1"
                }
            ],
            "hydra:totalItems": 5
        }');

        self::createClient()->request(Request::METHOD_GET, '/api/categories');
        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    // Item operations /////////////////////////////////////////////////////////////////////////////////////////////////

    public function testGet(): void
    {
        $this->authRequest(Request::METHOD_GET, '/api/categories/1');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJsonEquals('{
            "@context": "/api/contexts/Category",
            "@id": "/api/categories/1",
            "@type": "Category",
            "id": 1,
            "name": "Home",
            "enabled": true,
            "user": "/api/users/2"
        }');

        self::createClient()->request(Request::METHOD_GET, '/api/categories/1');
        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testUpdate(): void
    {
        $this->authRequest(Request::METHOD_PUT, '/api/categories/1', ['json' => ['name' => 'Car']]);
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJsonEquals('{
            "@context": "/api/contexts/Category",
            "@id": "/api/categories/1",
            "@type": "Category",
            "id": 1,
            "name": "Car",
            "enabled": true,
            "user": "/api/users/2"
        }');

        self::createClient()->request(Request::METHOD_PUT, '/api/categories/1');
        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testDelete(): void
    {
        $this->authRequest(Request::METHOD_DELETE, '/api/categories/1');
        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        self::createClient()->request(Request::METHOD_DELETE, '/api/categories/1');
        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }
}
