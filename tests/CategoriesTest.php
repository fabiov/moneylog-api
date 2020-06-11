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
        // Mario create a new category with Fabio's user id,
        // but user id is ignored and category is created with Mario's user id
        $this->marioRequest(Request::METHOD_POST, '/api/categories', ['json' => [
            'name' => 'Car', 'enabled' => true, 'user' => '/api/users/1'
        ]]);
        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonEquals('{
            "@context": "/api/contexts/Category",
            "@id": "/api/categories/6",
            "@type": "Category",
            "id": 6,
            "name": "Car",
            "enabled": true
        }');
        self::assertMatchesResourceItemJsonSchema(Category::class);
        self::createClient()->request(Request::METHOD_POST, '/api/categories');
        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testGetCollection(): void
    {
        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        $this->marioRequest(Request::METHOD_GET, '/api/categories');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJsonEquals('{
            "@context": "/api/contexts/Category",
            "@id": "/api/categories",
            "@type": "hydra:Collection",
            "hydra:member": [
                {
                    "@id": "/api/categories/2",
                    "@type": "Category",
                    "id": 2,
                    "name": "Home",
                    "enabled": true
                }
            ],
            "hydra:totalItems": 1
        }');
        self::createClient()->request(Request::METHOD_GET, '/api/categories');
        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    // ITEM OPERATIONS /////////////////////////////////////////////////////////////////////////////////////////////////

    public function testGetItem(): void
    {
        // Mario try to view a Fabio's category data
        $this->marioRequest(Request::METHOD_GET, '/api/categories/1');
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);

        // Fabio view his category
        $this->fabioRequest(Request::METHOD_GET, '/api/categories/1');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJsonEquals('{
            "@context": "/api/contexts/Category",
            "@id": "/api/categories/1",
            "@type": "Category",
            "id": 1,
            "name": "Auto",
            "enabled": true
        }');
        self::createClient()->request(Request::METHOD_GET, '/api/categories/1');
        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testUpdate(): void
    {
        // Mario try to modify a Fabio's category
        $this->marioRequest(Request::METHOD_PUT, '/api/categories/1', ['json' => ['name' => 'Car']]);
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);

        // Fabio modify his category
        $this->fabioRequest(Request::METHOD_PUT, '/api/categories/1', ['json' => ['name' => 'Car']]);
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJsonEquals('{
            "@context": "/api/contexts/Category",
            "@id": "/api/categories/1",
            "@type": "Category",
            "id": 1,
            "name": "Car",
            "enabled": true
        }');
        self::createClient()->request(Request::METHOD_PUT, '/api/categories/1');
        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testDelete(): void
    {
        $this->marioRequest(Request::METHOD_DELETE, '/api/categories/1');
        self::assertResponseStatusCodeSame(Response::HTTP_METHOD_NOT_ALLOWED);

        self::createClient()->request(Request::METHOD_DELETE, '/api/categories/1');
        self::assertResponseStatusCodeSame(Response::HTTP_METHOD_NOT_ALLOWED);
    }
}
