<?php
namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Class AbstractTest
 * @package App\Tests
 */
abstract class AbstractTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    /**
     * @var array
     */
    private $authMarioHeader;

    /**
     * @var array
     */
    private $authFabioHeader;

    protected function setUp()
    {
        parent::setUp();

        $authResponse = self::createClient()->request(Request::METHOD_POST, '/authentication_token', [
            'json' => [ 'email' => "mario.rossi@fixture.it", "password" => "mario123"]
        ])->toArray();
        $this->authMarioHeader = ['Authorization' => 'Bearer ' . $authResponse['token']];

        $authResponse = self::createClient()->request(Request::METHOD_POST, '/authentication_token', [
            'json' => [ 'email' => "fabio.ventura@fixture.it", "password" => "Fabio123"]
        ])->toArray();
        $this->authFabioHeader = ['Authorization' => 'Bearer ' . $authResponse['token']];
    }

    protected function marioRequest(string $method, string $url, array $options = []): ResponseInterface
    {
        $options = array_merge_recursive($options, ['headers' => $this->authMarioHeader]);
        return self::createClient()->request($method, $url, $options);
    }

    protected function fabioRequest(string $method, string $url, array $options = []): ResponseInterface
    {
        $options = array_merge_recursive($options, ['headers' => $this->authFabioHeader]);
        return self::createClient()->request($method, $url, $options);
    }

    /**
     * @param $filename
     * @param string $message
     */
    protected static function assertJsonEqualsFile($filename, $message = ''): void
    {
        self::assertJsonEquals(file_get_contents($filename), $message);
    }
}
