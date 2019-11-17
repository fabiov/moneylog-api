<?php
namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class UsersTest extends ApiTestCase
{
    public function testGetCollection(): void
    {
        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        static::createClient()->request('GET', '/api/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public function testCreate(): void
    {
        $response = static::createClient([], ['headers' => ['ACCEPT' => 'application/json']])->request('POST', '/api/users', ['json' => [
            'name'      => 'Mario',
            'surname'   => 'Rossi',
            'email'     => 'mario.rossi@mailinator.com',
            'password'  => 'myPassword',
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
//        $this->assertJsonContains([
//            "email"     => "mario.rossi@mailinator.com",
//            "roles"     => ["ROLE_USER"],
//            "name"      => "Mario",
//            "surname"   => "Rossi",
//            "lastLogin" => null,
//            "updated"   => null,
//        ]);

        $data = json_decode($response->getContent(), true);

//        $response = static::createClient([], ['headers' => ['ACCEPT' => 'application/json']])->request('PUT', '/api/users/'.$data['id'], ['json' => [
//            'name'  => 'Giuseppe',
//            'email' => 'giuseppe.rossi@mailinator.com',
//        ]]);
//        $this->assertResponseStatusCodeSame(201);
    }
}