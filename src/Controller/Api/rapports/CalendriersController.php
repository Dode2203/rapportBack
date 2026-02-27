<?php

namespace App\Controller\Api\rapports;

use App\Controller\Api\utils\BaseApiController;
use App\Dto\rapports\CalendrierDto;
use App\Dto\utils\OrderCriteria;
use App\Service\rapports\CalendriersService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Annotation\TokenRequired;
use App\Service\rapports\CalendriersUtilisateursService;
#[Route('/calendriers')]
class CalendriersController extends BaseApiController
{
    private CalendriersService $service;
    private CalendriersUtilisateursService $calendriersUtilisateursService;

    public function __construct(CalendriersService $service, CalendriersUtilisateursService $calendriersUtilisateursService)
    {
        $this->service = $service;
        $this->calendriersUtilisateursService = $calendriersUtilisateursService;
    }
    #[Route('', name: 'api_calendriers_create', methods: ['POST'])]
    #[TokenRequired(['Admin'])]
    public function createCalendrier(Request $request): JsonResponse
    {
        
        try {
            $dto = $this->serializer->deserialize(
                $request->getContent(),
                CalendrierDto::class,
                'json'
            );

            // Valider le DTO
            $errors = $this->validator->validate($dto);

            if (count($errors) > 0) {
                // $errorMessages = [];
                $messages = [];

                foreach ($errors as $error) {
                    $property = $error->getPropertyPath();
                    $message = $error->getMessage();

                    // erreurs par champ
                    // $errorMessages[$property][] = $message;

                    // message global
                    $messages[] = sprintf('%s : %s', $property, $message);
                }
                $erreurMessage = 'Erreur de validation : ' . implode(' | ', $messages);

                return $this->jsonError($erreurMessage, Response::HTTP_BAD_REQUEST);
            }
            $user = $this->getUserFromRequest($request);
            
            $rapportInsertArray = $this->service->insertDto($dto);
            return $this->jsonSuccess($rapportInsertArray);

        } catch (\Throwable $e) {
			return $this->jsonError($e->getMessage(), 400);
		}        
    }
    #[Route('', name: 'api_get_calendriers', methods: ['GET'])]
    #[TokenRequired]
    public function getCalendriers(Request $request): JsonResponse
    {
        try {
            $user = $this->getUserFromRequest($request);
            $listeCalendriers = $this->service->getAll(new OrderCriteria());
            $exclude = ['createdAt','deletedAt'];
            $listeCalendriersArray= $this->service->toArrayList($listeCalendriers,$exclude);
            return $this->jsonSuccess($listeCalendriersArray);  
            
        } catch (\Throwable $e) {
			return $this->jsonError($e->getMessage(), 400);
		} 
    }
    #[Route('/utilisateur', name: 'api_get_calendriers_utilisateur_disponible', methods: ['GET'])]
    #[TokenRequired]
    public function getCalendriersUtilisateurDisponible(Request $request): JsonResponse
    {
        try {
            $user = $this->getUserFromRequest($request);
            $dateDebut = new \DateTime('2026-01-01');;
            $dateFin = new \DateTime();
            $criteria = new OrderCriteria();
            $listeCalendriers = $this->calendriersUtilisateursService->getCalendrierDisponibleDate($user,$dateDebut,$dateFin,$criteria);
            $exclude = ['createdAt','deletedAt'];
            $listeCalendriersArray= $this->service->toArrayList($listeCalendriers,$exclude);
            return $this->jsonSuccess($listeCalendriersArray);  
            
        } catch (\Throwable $e) {
			return $this->jsonError($e->getMessage(), 400);
		} 
    }


}