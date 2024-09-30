<?php
namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NonMemberAccessTest extends WebTestCase
{
    public function testNonMemberCannotAccessCompanyOrProjects()
    {
        $client = static::createClient();

        // Authentification d'un utilisateur non membre
        $client->request('POST', '/api/login_check', [
            'json' => ['username' => 'nonmember@othercompany.com', 'password' => 'nonmember_password']
        ]);
        $data = json_decode($client->getResponse()->getContent(), true);
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        // Tentative d'accès à une société où l'utilisateur n'est pas membre
        $client->request('GET', '/api/companies/1');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());

        // Tentative d'accès à un projet de cette société
        $client->request('GET', '/api/projects/1');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }
}
