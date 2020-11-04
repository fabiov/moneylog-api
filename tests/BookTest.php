<?php
namespace App\Tests;

use App\Entity\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BookTest
 * @package App\Tests
 */
class BookTest extends AbstractTest
{
    // COLLECTION OPERATIONS ///////////////////////////////////////////////////////////////////////////////////////////
    public function testList(): void
    {
        // simple list
        self::createClient()->request(Request::METHOD_GET, '/api-library/books');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJsonEqualsFile(__DIR__ . '/json/books_collection.json');

        // filtered list with items
        self::createClient()->request(Request::METHOD_GET, '/api-library/books?title=m&isbn=97888&author.name=ca');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJsonEqualsFile(__DIR__ . '/json/books_filtered_collection.json');

        // filtered list without items
        self::createClient()->request(Request::METHOD_GET, '/api-library/books?author.name=Ventura');
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testDetail(): void
    {
        self::createClient()->request(Request::METHOD_GET, '/api-library/books/2');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJsonEqualsFile(__DIR__ . '/json/book_item_2.json');
    }

    public function testCreate(): void
    {
        self::createClient()->request(Request::METHOD_POST, '/api-library/books', ['json' => [
            "title" => "Sei personaggi in cerca d'autore",
            "isbn" => "9788806220571",
            "description" => "Capolavoro della letteratura del Novecento.",
            "price" => "9.5",
            "availability" => "53",
            "author" => [
                "id" => "2"
            ]
        ]]);
        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        self::assertResponseHeaderSame('content-type', 'application/json');
        self::assertJsonEquals('{
            "id": "5",
            "title": "Sei personaggi in cerca d\'autore",
            "isbn": "9788806220571",
            "description": "Capolavoro della letteratura del Novecento.",
            "price": "9.5",
            "availability": "53",
            "author": {
                "id": "2",
                "name": "Pirandello"
            }
        }');
    }
}
