<?php

namespace App\Controller;

use App\Entity\Flats;
use App\Repository\FlatsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * FlatsController.
 *
 * @Route("/api")
 */
class FlatsController extends AbstractController
{
    private $flatsRepository;
    private $requestStack;
    private $apiKey;

    /**
     * Constructor
     *
     * @param FlatsRepository $flatsRepository
     * @param RequestStack $requestStack
     * @param ParameterBagInterface $params
     */
    public function __construct(
        FlatsRepository $flatsRepository,
        RequestStack $requestStack,
        ParameterBagInterface $params
    ){
        $this->flatsRepository = $flatsRepository;
        $this->requestStack = $requestStack;
        $this->apiKey = $params->get('api_key');
    }

    /**
     * Check API key validity.
     *
     * @param string|null $apiKey The API key provided in the request.
     *
     * @throws AccessDeniedHttpException If the API key is invalid.
     */
    private function checkApiKey(?string $apiKey): void
    {
        if ($apiKey !== $this->apiKey) {
            throw new AccessDeniedHttpException('Invalid API key');
        }
    }
    
    /**
     * Get a list of flats with optional sorting, pagination, and search.
     *
     * @return JsonResponse The JSON response containing flat data.
     */
    #[Route('/api/flats/', methods: ['GET'])]
    public function getFlats()
    {
        // Retrieve query parameters
        $request = $this->requestStack->getCurrentRequest();

        $apiKey = $request->headers->get('X-API-KEY');

        $this->checkApiKey($apiKey);

        $sortField = $request->query->get('sortBy') ?: 'id';
        $sortOrder = $request->query->get('sortOrder') ?: 'ASC';
        $limit = $request->query->get('limit') ?: 10;
        $offset = $request->query->get('page') ?: 1;
        $search = $request->query->get('search');
        $offset = ($offset - 1) * $limit;

        // Retrieve flats from repository
        $flats = $this->flatsRepository->findAllOrderedByField($sortField, $sortOrder, $offset, $limit, $search);

        // Convert flats to array format
        $data = [];
        foreach ($flats as $flat) {
            $data[] = $this->flatToArray($flat);
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
        // Retrieve query parameters
        $request = $this->requestStack->getCurrentRequest();

        $apiKey = $request->headers->get('X-API-KEY');

        $this->checkApiKey($apiKey);

        // Retrieve flat from repository
        $flat = $this->flatsRepository->find($id);

        // Check if flat exists
        if (!$flat) {
            return new JsonResponse(['error' => 'Flat not found'], Response::HTTP_NOT_FOUND);
        }

        // Convert flat to array format
        $data = $this->flatToArray($flat);

        return new JsonResponse($data);
    }

    /**
     * Convert a Flat entity to array format.
     *
     * @param object $flat The Flat entity.
     *
     * @return array The array representation of the Flat entity.
     */
    private function flatToArray($flat): array
    {
        return [
            'id' => $flat->getId(),
            'name' => $flat->getName(),
            'city' => $flat->getCity(),
            'description' => $flat->getDescription(),
            'img' => $flat->getImg(),
        ];
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