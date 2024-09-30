<?php
// tests/Controller/SocieteControllerTest.php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User; // Assurez-vous d'importer la classe User
use Symfony\Component\DependencyInjection\Loader\Configurator\request;

class SocieteControllerTest extends WebTestCase
{
    // Méthode pour créer un utilisateur simulé avec un rôle spécifique
    protected function getUserByRole(string $role): User
    {
        $user = new User();
        $user->setRoles([$role]); // Assurez-vous que cette méthode existe dans votre entité User

        return $user;
    }

    public function testUserCanAccessSocieteAsAdmin()
    {
        $client = static::createClient();
        $user = $this->getUserByRole('ROLE_ADMIN'); // Créer un utilisateur avec le rôle admin
        $client->loginUser($user); // Connexion de l'utilisateur

        // Effectuer une requête GET sur l'API
        $client->request('GET', '/api/societes');

        // Vérifier que la réponse est réussie
        $this->assertResponseIsSuccessful();
        // Assurez-vous que le contenu attendu correspond bien au format JSON
        $this->assertJsonStringEqualsJsonString(
            json_encode(['nom' => 'Nom de la société']), // Exemple de contenu attendu
            $client->getResponse()->getContent()
        );
    }

    public function testUserCannotAccessSocieteAsConsultant()
    {
        $client = static::createClient();

    }
}
