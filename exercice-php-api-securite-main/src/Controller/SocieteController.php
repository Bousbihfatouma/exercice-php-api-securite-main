<?php

namespace App\Controller;

use App\Entity\Societe;
use App\Repository\SocieteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/societes')]
class SocieteController extends AbstractController
{
    private SocieteRepository $societeRepository;

    public function __construct(SocieteRepository $societeRepository)
    {
        $this->societeRepository = $societeRepository;
    }

    #[Route('', methods: ['GET'])]
    public function index(): Response
    {
        $societes = $this->societeRepository->findAll();
        return $this->json($societes);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(int $id): Response
    {
        $societe = $this->societeRepository->find($id);
        if (!$societe) {
            throw $this->createNotFoundException('Societe not found');
        }
        return $this->json($societe);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $societe = new Societe();
        $societe->setNom($data['nom']);
        $societe->setNumeroSiret($data['numeroSiret']);
        $societe->setAdresse($data['adresse']);

        $this->societeRepository->save($societe, true);

        return $this->json($societe, Response::HTTP_CREATED);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, int $id): Response
    {
        $societe = $this->societeRepository->find($id);
        if (!$societe) {
            throw $this->createNotFoundException('Societe not found');
        }

        $data = json_decode($request->getContent(), true);
        if (isset($data['nom'])) {
            $societe->setNom($data['nom']);
        }
        if (isset($data['numeroSiret'])) {
            $societe->setNumeroSiret($data['numeroSiret']);
        }
        if (isset($data['adresse'])) {
            $societe->setAdresse($data['adresse']);
        }

        $this->societeRepository->save($societe, true);

        return $this->json($societe);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        $societe = $this->societeRepository->find($id);
        if (!$societe) {
            throw $this->createNotFoundException('Societe not found');
        }

        $this->societeRepository->remove($societe, true);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
