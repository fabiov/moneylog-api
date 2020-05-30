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
            "id": 1,
            "user": "/api/users/1",
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

        // Fabio modify his setting
        $this->fabioRequest(Request::METHOD_PUT, '/api/settings/1', ['json' => ['payday' => 10]]);
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJsonEquals('{
            "@context": "/api/contexts/Setting",
            "@id": "/api/settings/1",
            "@type": "Setting",
            "id": 1,
            "user": "/api/users/1",
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
