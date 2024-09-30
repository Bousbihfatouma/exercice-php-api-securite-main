<?php
namespace App\Tests\Functional;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class AccessRestrictionTest extends ApiTestCase
{
    public function testNonMemberCannotAccessProject()
    {
        // Simuler la connexion d'un utilisateur non membre
        $client = self::createClient();
        $client->request('GET', '/api/projects/1', [
            'headers' => ['Authorization' => 'Bearer TOKEN_NON_MEMBRE'],
        ]);

        // Vérifier que l'accès est refusé
        $this->assertResponseStatusCodeSame(403);
    }

    public function testNonMemberCannotAccessCompany()
    {
        $client = self::createClient();
        $client->request('GET', '/api/companies/1', [
            'headers' => ['Authorization' => 'Bearer TOKEN_NON_MEMBRE'],
        ]);

        $this->assertResponseStatusCodeSame(403);
    }
}
