<?php
namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;

class UserAccessTest extends WebTestCase
{
    public function testAccessToCompanyAndProjectsByRole()
    {
        $client = static::createClient();

        // Authentification d'un utilisateur Admin
        $client->request('POST', '/api/login_check', [
            'json' => ['username' => 'admin@company.com', 'password' => 'admin_password']
        ]);
        $data = json_decode($client->getResponse()->getContent(), true);
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        // Vérification de l'accès à une société et un projet pour l'admin
        $client->request('GET', '/api/companies/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $client->request('GET', '/api/projects/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Authentification d'un utilisateur Manager
        $client->request('POST', '/api/login_check', [
            'json' => ['username' => 'manager@company.com', 'password' => 'manager_password']
        ]);
        $data = json_decode($client->getResponse()->getContent(), true);
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        // Vérification de l'accès à une société et un projet pour le manager
        $client->request('GET', '/api/companies/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $client->request('GET', '/api/projects/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Authentification d'un utilisateur Consultant
        $client->request('POST', '/api/login_check', [
            'json' => ['username' => 'consultant@company.com', 'password' => 'consultant_password']
        ]);
        $data = json_decode($client->getResponse()->getContent(), true);
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        // Vérification de l'accès en lecture seule aux projets pour le consultant
        $client->request('GET', '/api/projects/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Tentative de modification par un consultant (doit échouer)
        $client->request('PUT', '/api/projects/1', [
            'json' => ['name' => 'New Project Name']
        ]);
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }
}
