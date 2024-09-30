<?php

namespace App\Controller;

use App\Entity\UserSocieteRole;
use App\Repository\UserSocieteRoleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/user_societe_roles')]
class UserSocieteRoleController extends AbstractController
{
    private UserSocieteRoleRepository $userSocieteRoleRepository;

    public function __construct(UserSocieteRoleRepository $userSocieteRoleRepository)
    {
        $this->userSocieteRoleRepository = $userSocieteRoleRepository;
    }

    #[Route('', methods: ['GET'])]
    public function index(): Response
    {
        $roles = $this->userSocieteRoleRepository->findAll();
        return $this->json($roles);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(int $id): Response
    {
        $role = $this->userSocieteRoleRepository->find($id);
        if (!$role) {
            throw $this->createNotFoundException('UserSocieteRole not found');
        }
        return $this->json($role);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $userSocieteRole = new UserSocieteRole();
        // Récupérer et associer l'utilisateur et la société appropriés
        // $user = $this->getDoctrine()->getRepository(User::class)->find($data['userId']);
        // $societe = $this->getDoctrine()->getRepository(Societe::class)->find($data['societeId']);
        // $userSocieteRole->setUser($user);
        // $userSocieteRole->setSociete($societe);
        $userSocieteRole->setRole($data['role']);

        $this->userSocieteRoleRepository->save($userSocieteRole, true);

        return $this->json($userSocieteRole, Response::HTTP_CREATED);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, int $id): Response
    {
        $role = $this->userSocieteRoleRepository->find($id);
        if (!$role) {
            throw $this->createNotFoundException('UserSocieteRole not found');
        }

        $data = json_decode($request->getContent(), true);
        if (isset($data['role'])) {
            $role->setRole($data['role']);
        }

        $this->userSocieteRoleRepository->save($role, true);

        return $this->json($role);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        $role = $this->userSocieteRoleRepository->find($id);
        if (!$role) {
            throw $this->createNotFoundException('UserSocieteRole not found');
        }

        $this->userSocieteRoleRepository->remove($role, true);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
