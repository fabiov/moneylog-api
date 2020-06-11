<?php
namespace App\Tests;

use App\Entity\Account;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AccountsTest
 * @package App\Tests
 */
class SettingsTest extends AbstractTest
{
    // COLLECTION OPERATIONS ///////////////////////////////////////////////////////////////////////////////////////////

    public function testCreate(): void
    {
        // User can not create a new setting
        $this->marioRequest(Request::METHOD_POST, '/api/settings', [
            'json' => ['user' => '/api/user/1', 'payday' => 12, 'months' => 18, 'provisioning' => true]
        ]);
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testGetCollection(): void
    {
        $this->marioRequest(Request::METHOD_GET, '/api/settings');
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    // ITEM OPERATIONS /////////////////////////////////////////////////////////////////////////////////////////////////

    public function testGetItem(): void
    {
        // Mario try to view a Fabio's setting data
        $this->marioRequest(Request::METHOD_GET, '/api/settings/1');
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);

        // Fabio view his setting data
        $this->fabioRequest(Request::METHOD_GET, '/api/settings/1');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJsonEquals('{
            "@context": "/api/contexts/Setting",
            "@id": "/api/settings/1",
            "@type": "Setting",
            "payday": 12,
            "months": 18,
            "provisioning": true
        }');
    }

    public function testUpdate(): void
    {
        // Mario tries to modify a Fabio's setting
        $this->marioRequest(Request::METHOD_PUT, '/api/settings/1', ['json' => ['payday' => 10]]);
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);

        // Fabio modify his setting with bad data
        $this->fabioRequest(Request::METHOD_PUT, '/api/settings/1', ['json' => [
            'payday' => 29,  'months' => 1
        ]]);
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        self::assertJsonEquals('{
            "@context": "/api/contexts/ConstraintViolationList",
            "@type": "ConstraintViolationList",
            "hydra:title": "An error occurred",
            "hydra:description": "payday: You must be between 1 and 28\nmonths: This value should be greater than or equal to 2.",
            "violations": [
                {"propertyPath": "payday", "message": "You must be between 1 and 28"},
                {"propertyPath": "months", "message": "This value should be greater than or equal to 2."}
            ]
        }');

        // Fabio modify his setting
        $this->fabioRequest(Request::METHOD_PUT, '/api/settings/1', ['json' => ['payday' => 10]]);
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJsonEquals('{
            "@context": "/api/contexts/Setting",
            "@id": "/api/settings/1",
            "@type": "Setting",
            "payday": 10,
            "months": 18,
            "provisioning": true
        }');
    }

    public function testDelete(): void
    {
        $this->marioRequest(Request::METHOD_DELETE, '/api/settings/1');
        self::assertResponseStatusCodeSame(Response::HTTP_METHOD_NOT_ALLOWED);
    }
}
