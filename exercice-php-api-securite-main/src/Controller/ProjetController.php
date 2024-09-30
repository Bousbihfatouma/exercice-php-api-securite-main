<?php



namespace App\Controller;

use App\Entity\Projet;
use App\Repository\ProjetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/projets')]
class ProjetController extends AbstractController
{
    private ProjetRepository $projetRepository;

    public function __construct(ProjetRepository $projetRepository)
    {
        $this->projetRepository = $projetRepository;
    }

    #[Route('', methods: ['GET'])]
    public function index(): Response
    {
        $projets = $this->projetRepository->findAll();
        return $this->json($projets);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(int $id): Response
    {
        $projet = $this->projetRepository->find($id);
        if (!$projet) {
            throw $this->createNotFoundException('Projet not found');
        }
        return $this->json($projet);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $projet = new Projet();
        $projet->setTitre($data['titre']);
        $projet->setDescription($data['description']);
        $projet->setCreatedAt(new \DateTime());

        // Assure-toi de passer la société appropriée
        // $societe = $this->getDoctrine()->getRepository(Societe::class)->find($data['societeId']);
        // $projet->setSociete($societe);

        $this->projetRepository->save($projet, true);

        return $this->json($projet, Response::HTTP_CREATED);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, int $id): Response
    {
        $projet = $this->projetRepository->find($id);
        if (!$projet) {
            throw $this->createNotFoundException('Projet not found');
        }

        $data = json_decode($request->getContent(), true);
        if (isset($data['titre'])) {
            $projet->setTitre($data['titre']);
        }
        if (isset($data['description'])) {
            $projet->setDescription($data['description']);
        }

        $this->projetRepository->save($projet, true);

        return $this->json($projet);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        $projet = $this->projetRepository->find($id);
        if (!$projet) {
            throw $this->createNotFoundException('Projet not found');
        }

        $this->projetRepository->remove($projet, true);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
