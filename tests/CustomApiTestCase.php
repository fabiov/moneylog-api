<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class CustomApiTestCase extends ApiTestCase
{
    protected function createUser(string $email, string $password): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setName('');
        $user->setSurname('');
        $encoded = self::$container->get('security.password_encoder')->encodePassword($user, $password);
        $user->setPassword($encoded);
        $em = self::$container->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();
        return $user;
    }

    protected function getAuthenticatedClient(string $email, string $password)
    {
        $response = static::createClient([], ['headers' => ['ACCEPT' => 'application/json']])
            ->request(Request::METHOD_POST, '/authentication_token', ['json' => [
                'email' => $email, 'password'  => $password
            ]]);
        $tokenData = json_decode($response->getContent(), true);

        return static::createClient([], ['headers' => ['Authorization' => 'Bearer ' . $tokenData['token']]]);
    }

    protected function createUserAndGetAuthenticatedClient(string $email, string $password)
    {
        $this->createUser($email, $password);
        return $this->getAuthenticatedClient($email, $password);
    }
}