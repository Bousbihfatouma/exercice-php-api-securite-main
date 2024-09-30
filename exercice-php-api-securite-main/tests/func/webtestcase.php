<?php
namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjectAccessControlTest extends WebTestCase
{
    public function testProjectCreationByAdmin()
    {
        $client = static::createClient();

        // Authentification d'un utilisateur Admin
        $client->request('POST', '/api/login_check', [
            'json' => ['username' => 'admin@company.com', 'password' => 'admin_password']
        ]);
        $data = json_decode($client->getResponse()->getContent(), true);
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        // Tentative de création d'un projet par l'admin
        $client->request('POST', '/api/projects', [
            'json' => ['name' => 'New Project', 'description' => 'Description', 'company' => '/api/companies/1']
        ]);
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }

    public function testProjectModificationByManager()
    {
        $client = static::createClient();

        // Authentification d'un utilisateur Manager
        $client->request('POST', '/api/login_check', [
            'json' => ['username' => 'manager@company.com', 'password' => 'manager_password']
        ]);
        $data = json_decode($client->getResponse()->getContent(), true);
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        // Tentative de modification d'un projet par le manager
        $client->request('PUT', '/api/projects/1', [
            'json' => ['name' => 'Updated Project Name']
        ]);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testProjectDeletionByConsultantFails()
    {
        $client = static::createClient();

        // Authentification d'un utilisateur Consultant
        $client->request('POST', '/api/login_check', [
            'json' => ['username' => 'consultant@company.com', 'password' => 'consultant_password']
        ]);
        $data = json_decode($client->getResponse()->getContent(), true);
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        // Tentative de suppression d'un projet par un consultant (doit échouer)
        $client->request('DELETE', '/api/projects/1');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }
}
