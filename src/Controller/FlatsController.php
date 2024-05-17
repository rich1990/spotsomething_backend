<?php

namespace App\Controller;

use App\Entity\Flats;
use App\Repository\FlatsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class FlatsController extends AbstractController
{
    private $flatsRepository;

    public function __construct(FlatsRepository $flatsRepository)
    {
        $this->flatsRepository = $flatsRepository;
    }

    #[Route('/api/flats/', methods: ['GET'])]
    public function getFlats()
    {
        $flats = $this->flatsRepository->findAll();

        // Convert products to array
        $data = [];
        foreach ($flats as $flat) {
            $data[] = [
                'id' => $flat->getId(),
                'name' => $flat->getName(),
                'description' => $flat->getDescription(),
                'img' => $flat->getImg(),
            ];
        }
    
        return new JsonResponse($data);
    }

    #[Route('/api/flats/{id}', methods: ['GET'])]
    public function getFlat($id)
    {
        $flat = $this->flatsRepository->find($id);

        if (!$flat) {
            return new JsonResponse(['error' => 'Flat not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($flat);
    }

}