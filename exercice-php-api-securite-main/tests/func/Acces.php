<?php

namespace App\Tests\Func;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjectAccessApiTest extends WebTestCase
{
    public function testAccessProjectAsConsultant()
    {
        $client = static::createClient();

        // Authentifier l'utilisateur consultant
        $client->request('POST', '/api/login', [
            'email' => 'consultant@example.com',
            'password' => 'password',
        ]);

        $response = $client->getResponse();
        $this->assertResponseIsSuccessful();
        $token = json_decode($response->getContent(), true)['token'];

        // Essayer d'accéder à un projet
        $client->setServerParameters(['HTTP_AUTHORIZATION' => 'Bearer ' . $token]);
        $client->request('GET', '/api/projects/1'); // Remplacez par un ID valide

        $this->assertResponseStatusCodeSame(403); // Attendez un accès refusé
    }
}
