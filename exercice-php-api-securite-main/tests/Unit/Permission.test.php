<?php
namespace App\test\Unit;

use App\Entity\User;
use App\Entity\Projet;
use App\Entity\UserSocieteRole;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProjectAccessTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::$container->get(EntityManagerInterface::class);
    }

    public function testConsultantCannotEditProject()
    {
        // Création d'un utilisateur consultant
        $consultant = new User();
        $consultant->setEmail('consultant@example.com');
        $consultant->setRoles(['ROLE_CONSULTANT']);

        // Création d'un projet
        $project = new Projet();
        $project->setTitre('Test Project');
        $project->setDescription('Description for test project');

        // Enregistrement des entités
        $this->entityManager->persist($consultant);
        $this->entityManager->persist($project);
        $this->entityManager->flush();

        // Ajout du rôle de consultant à l'utilisateur pour le projet
        $userSocieteRole = new UserSocieteRole();
        $userSocieteRole->setUser($consultant);
        $userSocieteRole->setSociete($project->getSociete()); // Assurez-vous que le projet a une société
        $userSocieteRole->setRole('consultant');

        // Tentative de modification du projet
        $this->expectException(\Exception::class);
        $project->setTitre('Modified Title'); // Ceci devrait lever une exception pour un consultant
    }
}
