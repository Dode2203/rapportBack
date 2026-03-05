<?php

namespace App\Controller\Api\rapports;

use App\Controller\Api\utils\BaseApiController;
use App\Dto\rapports\ActiviteCollectionDto;
use App\Dto\utils\OrderCriteria;
use App\Service\rapports\CalendriersUtilisateursService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Annotation\TokenRequired;

#[Route('/rapports')]
class RapportsController extends BaseApiController
{
    private CalendriersUtilisateursService $cus;

    public function __construct(CalendriersUtilisateursService $calendriersUtilisateursService)
    {
        $this->cus = $calendriersUtilisateursService;
    }
    #[Route('', name: 'api_rapports_create', methods: ['POST'])]
    #[TokenRequired]
    public function createRapport(Request $request): JsonResponse
    {
        
        try {
            $dto = $this->serializer->deserialize(
                $request->getContent(),
                ActiviteCollectionDto::class,
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
            
            $rapportInsert = $this->cus->insertRapportDto($user, $dto);
            $rapportInsertArray = $rapportInsert->toArray();
            return $this->jsonSuccess($rapportInsertArray);

        } catch (\Throwable $e) {
			return $this->jsonError($e->getMessage(), 400);
		}        
    }
    #[Route('', name: 'api_rapports_get', methods: ['GET'])]
    #[TokenRequired]
    public function getRapport(Request $request): JsonResponse
    {
        try {
            $user = $this->getUserFromRequest($request);
            $listeRapports = $this->cus->getByUtilisateur($user);
            $listeRapportsArray= $this->cus->transformerArray($listeRapports);
            return $this->jsonSuccess($listeRapportsArray);
            
        } catch (\Throwable $e) {
			return $this->jsonError($e->getMessage(), 400);
		} 
    }
    #[Route('/calendrier', name: 'api_rapports_calendrier', methods: ['GET'])]
    #[TokenRequired(['Admin','Supervisor'])]
    public function getRapportByCalendrier(Request $request): JsonResponse
    {
        try {   
            $idCalendrier = $request->query->get('idCalendrier');

            if (!$idCalendrier) {
                return $this->jsonError('Paramètre idCalendrier requis', 400);
            }
            $listeRapports = $this->cus->getByCalendrierId($idCalendrier);
            $listeRapportsArray= $this->cus->transformerArray($listeRapports);
            return $this->jsonSuccess($listeRapportsArray);
            
        } catch (\Throwable $e) {
			return $this->jsonError($e->getMessage(), 400);
		} 
    }
    #[Route('/changerValidation', name: 'api_rapports_changer_validation', methods: ['POST'])]
    #[TokenRequired(['Admin','Supervisor'])]
    public function changerValidation(Request $request): JsonResponse
    {
        try {  
            $data = json_decode($request->getContent(), true);

            $requiredFields = ['id'];
            $this->validatorService->validateRequiredFields($data,$requiredFields);
            $id = $data['id'];
            $rapport = $this->cus->changerStatusValidationId($id);
            $rapportArray = $rapport->toArray();
            return $this->jsonSuccess($rapportArray);
            
        } catch (\Throwable $e) {
			return $this->jsonError($e->getMessage(), 400);
		} 
    }
    #[Route('/{idCalendrierUtilisateur}', name: 'api_rapports_modifier', methods: ['PUT'])]
    #[TokenRequired]
    public function modifierRapport(int $idCalendrierUtilisateur, Request $request): JsonResponse
    {
        try {
            $dto = $this->serializer->deserialize(
                $request->getContent(),
                ActiviteCollectionDto::class,
                'json'
            );

            $errors = $this->validator->validate($dto);

            if (count($errors) > 0) {
                $messages = [];
                foreach ($errors as $error) {
                    $messages[] = sprintf(
                        '%s : %s',
                        $error->getPropertyPath(),
                        $error->getMessage()
                    );
                }

                return $this->jsonError(
                    'Erreur de validation : ' . implode(' | ', $messages),
                    Response::HTTP_BAD_REQUEST
                );
            }

            $user = $this->getUserFromRequest($request);

            $rapportInsert = $this->cus->modifierRapport($user, $dto, $idCalendrierUtilisateur);
            $rapportArray = $this->cus->toArray($rapportInsert);
            return $this->jsonSuccess($rapportArray);

        } catch (\Throwable $e) {
            return $this->jsonError($e->getMessage(), 400);
        }
    }
    #[Route('/historique', name: 'api_rapports_get_historique', methods: ['GET'])]
    #[TokenRequired(['Admin','Supervisor'])]
    public function getRapportHistorique(Request $request): JsonResponse
    {
        try {
            $data = [
                'idUtilisateur' => $request->query->get('idUtilisateur'),
                'idCalendrier'  => $request->query->get('idCalendrier'),
            ];

            $requiredFields = ['idUtilisateur', 'idCalendrier'];
            $this->validatorService->validateRequiredFields($data, $requiredFields);

            $idUtilisateur = $data['idUtilisateur'];
            $idCalendrier  = $data['idCalendrier'];

            $listeRapports = $this->cus->getByCalendrierAndUtilisateurDeletedAtId($idUtilisateur,$idCalendrier);
            $listeRapportsArray= $this->cus->transformerArray($listeRapports);
            return $this->jsonSuccess($listeRapportsArray);
            
        } catch (\Throwable $e) {
			return $this->jsonError($e->getMessage(), 400);
		} 
    }
    #[Route('/recherche', name: 'api_rapports_recherche', methods: ['GET'])]
    #[TokenRequired]
    public function getRapportRecherche(Request $request): JsonResponse
    {
        try {
            $data = [
                'date' => $request->query->get('date'),
            ];

            $user = $this->getUserFromRequest($request);
            $requiredFields = ['date'];
            $this->validatorService->validateRequiredFields($data, $requiredFields);

            $date = $data['date'];
   
            $listeRapports = $this->cus->getAllCalendrierByDate($user, new \DateTimeImmutable($date),new OrderCriteria());
            $listeRapportsArray= $this->cus->transformerArray($listeRapports);
            return $this->jsonSuccess($listeRapportsArray);
            
        } catch (\Throwable $e) {
			return $this->jsonError($e->getMessage(), 400);
		} 
    }
    

    


}