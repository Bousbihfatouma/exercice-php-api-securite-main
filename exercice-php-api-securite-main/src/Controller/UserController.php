<?php
namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/api/users')]
class UserController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Route('', methods: ['GET'])]
    public function index(): Response
    {
        $users = $this->userRepository->findAll();
        return $this->json($users);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(int $id): Response
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        return $this->json($user);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $data = json_decode($request->getContent(), true);
        $user = new User();
        $user->setEmail($data['email']);
        $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        $this->userRepository->save($user, true);

        return $this->json($user, Response::HTTP_CREATED);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, int $id, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $data = json_decode($request->getContent(), true);
        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }
        // Pour mettre à jour le mot de passe, il faut le hacher
        if (isset($data['password'])) {
            $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
        }

        $this->userRepository->save($user, true);

        return $this->json($user);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $this->userRepository->remove($user, true);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
