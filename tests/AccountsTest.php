<?php
namespace App\Tests;

use App\Entity\Account;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AccountsTest extends CustomApiTestCase {

    public function testCreateAccounts()
    {
        $client = self::createClient();
        $client->request(Request::METHOD_POST, '/api/accounts', ['json' => []]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);

        $mainUser         = $this->createUser('account.please@test.com', 'foo');
        $otherUser        = $this->createUser('other.user@test.com', 'foo');
        $authorizedClient = $this->createAuthenticatedClient('account.please@test.com', 'foo');

        $accountData = ['name' => 'test current account', 'recap' => true];
        $authorizedClient->request(Request::METHOD_POST, '/api/accounts', ['json' => $accountData]);
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $data = ['name' => 'test wrong user', 'recap' => true, 'user' => '/api/users/' . $otherUser->getId()];
        $authorizedClient->request(Request::METHOD_POST, '/api/accounts', ['json' => $data]);
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $listResponse = $authorizedClient->request(Request::METHOD_GET, '/api/accounts', ['json' => []]);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $listData = json_decode($listResponse->getContent(), true);
        $this->assertEquals(2, $listData['hydra:totalItems']);
    }

    public function testUpdateAccount()
    {
        $account = new Account();
        $account->setUser($this->otherUser);
        $account->setRecap(true);
        $account->setName('test update other user');
        $em = self::$container->get('doctrine')->getManager();
        $em->persist($account);
        $em->flush();

        // un utente non può modificare un account di un altro utente
//        $this->authorizedClient->request('PUT', '/api/cheeses/' . $account->getId(), [
//            // try to trick security by reassigning to this user
//            'json' => ['name' => 'test update other user updated', 'owner' => '/api/users/' . $this->otherUser->getId()]
//        ]);
//        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN, 'only author can updated');
//        $this->logIn($client, 'user1@example.com', 'foo');
//        $client->request('PUT', '/api/cheeses/'.$cheeseListing->getId(), [
//            'json' => ['title' => 'updated']
//        ]);
//        $this->assertResponseStatusCodeSame(200);
    }
}
