<?php

namespace App\Controller;

use App\Entity\Flats;
use App\Repository\FlatsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * FlatsController.
 *
 * @Route("/api")
 */
class FlatsController extends AbstractController
{
    private $flatsRepository;
    private $requestStack;

    public function __construct(FlatsRepository $flatsRepository, RequestStack $requestStack)
    {
        $this->flatsRepository = $flatsRepository;
        $this->requestStack = $requestStack;
    }

    /**
     * Get a list of flats with optional sorting, pagination, and search.
     *
     * @return JsonResponse The JSON response containing flat data.
     */
    #[Route('/api/flats/', methods: ['GET'])]
    public function getFlats()
    {
        $request = $this->requestStack->getCurrentRequest();
        $sortField = $request->query->get('sortBy') ?: 'id';
        $sortOrder = $request->query->get('sortOrder') ?: 'ASC';
        $limit = $request->query->get('limit') ?: 10;
        $offset = $request->query->get('page') ?: 1;

        $search = $request->query->get('search');

        $offset = ($offset - 1) * $limit;

        $flats = $this->flatsRepository->findAllOrderedByField($sortField, $sortOrder, $offset, $limit, $search);

        // Convert flats to array format
        $data = [];
        foreach ($flats as $flat) {
            $data[] = [
                'id' => $flat->getId(),
                'name' => $flat->getName(),
                'city' => $flat->getCity(),
                'description' => $flat->getDescription(),
                'img' => $flat->getImg(),
            ];
        }
    
        return new JsonResponse($data);
    }

    /**
     * Get details of a single flat by its ID.
     *
     * @param int $id The ID of the flat.
     *
     * @return JsonResponse The JSON response containing flat details.
     */
    #[Route('/api/flats/{id}', methods: ['GET'])]
    public function getFlat($id)
    {
        $flat = $this->flatsRepository->find($id);

        if (!$flat) {
            return new JsonResponse(['error' => 'Flat not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($flat);
    }

    /**
     * Default action for the controller.
     *
     * @return Response The HTTP response.
     */
    #[Route('/', methods: ['GET'])]
    public function default()
    {
        echo 'This is a microservice, open Spotaroom frontend application, or use the APIs';
        exit;
    }
}