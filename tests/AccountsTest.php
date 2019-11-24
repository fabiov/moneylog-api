<?php
namespace App\Tests;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AccountsTest extends CustomApiTestCase {

    public function testCreateAccounts()
    {
        $client = self::createClient();
        $client->request(Request::METHOD_POST, '/api/accounts', ['json' => []]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);

        $authenticatedUser   = $this->createUser('account.please@test.com', 'foo');
        $otherUser           = $this->createUser('other.user@test.com', 'foo');
        $authenticatedClient = $this->getauthenticatedClient('account.please@test.com', 'foo');

        $accountData = ['name' => 'Conto corrente', 'recap' => true];
        $authenticatedClient->request(Request::METHOD_POST, '/api/accounts', ['json' => $accountData]);
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

//        $client->request(Request::METHOD_POST, '/api/accounts', [
//            'json' => $accountData + ['user' => '/api/users/' . $otherUser->getId()],
//        ]);
//        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST, 'Not passing the correct owner');
//
//        $client->request(Request::METHOD_POST, '/api/accounts', [
//            'json' => $accountData + ['owner' => '/api/users/' . $authenticatedUser->getId()],
//        ]);
//        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED201);
    }

//    public function testUpdateCheeseListing()
//    {
//        $client = self::createClient();
//        $user1 = $this->createUser('user1@example.com', 'foo');
//        $user2 = $this->createUser('user2@example.com', 'foo');
//        $cheeseListing = new CheeseListing('Block of cheddar');
//        $cheeseListing->setOwner($user1);
//        $cheeseListing->setPrice(1000);
//        $cheeseListing->setDescription('mmmm');
//        $em = $this->getEntityManager();
//        $em->persist($cheeseListing);
//        $em->flush();
//        $this->logIn($client, 'user2@example.com', 'foo');
//        $client->request('PUT', '/api/cheeses/'.$cheeseListing->getId(), [
//            // try to trick security by reassigning to this user
//            'json' => ['title' => 'updated', 'owner' => '/api/users/'.$user2->getId()]
//        ]);
//        $this->assertResponseStatusCodeSame(403, 'only author can updated');
//        $this->logIn($client, 'user1@example.com', 'foo');
//        $client->request('PUT', '/api/cheeses/'.$cheeseListing->getId(), [
//            'json' => ['title' => 'updated']
//        ]);
//        $this->assertResponseStatusCodeSame(200);
//    }

}
