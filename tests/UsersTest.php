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
        static::createClient()->request(Request::METHOD_GET, '/api/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public function testLifeCycle(): void
    {
        // CREATE NEW USER
        $response = static::createClient([], ['headers' => ['ACCEPT' => 'application/json']])
            ->request(Request::METHOD_POST, '/api/users', ['json' => [
                'name'      => 'Mario',
                'surname'   => 'Rossi',
                'email'     => 'mario.rossi@mailinator.com',
                'password'  => 'myPassword',
            ]]);
        $data = json_decode($response->getContent(), true);
        $userId = $data['id'];

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
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
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        $this->assertJsonEquals('{"code": 401,"message": "Bad credentials."}');

        // check if created user can be authenticated
        $response = static::createClient([], ['headers' => ['ACCEPT' => 'application/json']])
            ->request(Request::METHOD_POST, '/authentication_token', ['json' => [
                'email'     => 'mario.rossi@mailinator.com',
                'password'  => 'myPassword',
            ]]);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $tokenData = json_decode($response->getContent(), true);
        $token  = $tokenData['token'];

        // MODIFY USER DATA
        $modifyResponse = static::createClient([], ['headers' => ['ACCEPT' => 'application/json', 'Authorization' => "Bearer $token"]])
            ->request(Request::METHOD_PUT, "/api/users/$userId", ['json' => [
                'name'    => 'Mario Filippo',
                'surname' => 'Rossi Martini',
            ]]);
        $modifyData = json_decode($modifyResponse->getContent(), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals('Mario Filippo', $modifyData['name']);
        $this->assertEquals('Rossi Martini', $modifyData['surname']);

        // USER CAN NOT BE DELETED
        static::createClient()->request(Request::METHOD_DELETE, "/api/users/$userId");
        $this->assertResponseStatusCodeSame(Response::HTTP_METHOD_NOT_ALLOWED);
    }
}