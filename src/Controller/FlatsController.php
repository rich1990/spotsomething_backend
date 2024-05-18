<?php

namespace App\Controller;

use App\Entity\Flats;
use App\Repository\FlatsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @Route("/api")
 */
class FlatsController extends AbstractController
{
    private $flatsRepository;
    private $requestStack;

    public function __construct(FlatsRepository $flatsRepository,RequestStack $requestStack)
    {
        $this->flatsRepository = $flatsRepository;
        $this->requestStack = $requestStack;
    }

    #[Route('/api/flats/', methods: ['GET'])]
    public function getFlats()
    {
        $request = $this->requestStack->getCurrentRequest();
        $sortField = $request->query->get('sortBy');
        $sortOrder = $request->query->get('sortOrder');
        $limit = $request->query->get('limit');
        $page = $request->query->get('page');

        $flats = $this->flatsRepository->findAllOrderedByField($sortField, $sortOrder, $page, $limit);

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