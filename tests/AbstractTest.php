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
    private $authHeader;

    protected function setUp()
    {
        parent::setUp();
        $authResponse = self::createClient()->request(
            Request::METHOD_POST,
            '/authentication_token',
            ['json' => [ 'email' => "mario.rossi@fixture.it", "password" => "mario123"]]
        )->toArray();
        $this->authHeader = ['Authorization' => 'Bearer ' . $authResponse['token']];
    }

    protected function authRequest(string $method, string $url, array $options = []): ResponseInterface
    {
        $options = array_merge_recursive($options, ['headers' => $this->authHeader]);
        return self::createClient()->request($method, $url, $options);
    }
}
