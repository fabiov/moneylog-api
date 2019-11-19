<?php
namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersTest extends ApiTestCase
{
    public function testGetCollection(): void
    {
        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        static::createClient()->request('GET', '/api/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public function testLifeCycle(): void
    {
        // CREATE NEW USER
        $response = static::createClient([], ['headers' => ['ACCEPT' => 'application/json']])->request('POST', '/api/users', ['json' => [
            'name'      => 'Mario',
            'surname'   => 'Rossi',
            'email'     => 'mario.rossi@mailinator.com',
            'password'  => 'myPassword',
        ]]);
        $data = json_decode($response->getContent(), true);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertEquals("mario.rossi@mailinator.com", $data["email"]);
        $this->assertEquals("Mario",$data["name"]);
        $this->assertEquals("Rossi",$data["surname"]);
        $this->assertNull($data["lastLogin"]);
        $this->assertNull($data["updated"]);

        // try authentication with wrong password
        static::createClient([], ['headers' => ['ACCEPT' => 'application/json']])
            ->request(Request::METHOD_POST, '/authentication_token', ['json' => [
                'email'     => 'mario.rossi@mailinator.com',
                'password'  => 'wrongPassword',
            ]]);
        $this->assertResponseStatusCodeSame(401);
        $this->assertJsonEquals('{"code": 401,"message": "Bad credentials."}');

        // check if created user can be authenticated
        $response = static::createClient([], ['headers' => ['ACCEPT' => 'application/json']])
            ->request(Request::METHOD_POST, '/authentication_token', ['json' => [
                'email'     => 'mario.rossi@mailinator.com',
                'password'  => 'myPassword',
            ]]);
        $this->assertResponseStatusCodeSame(200);

        $tokenData = json_decode($response->getContent(), true);
        $token = $tokenData['token'];

        // MODIFY USER DATA
    }
}