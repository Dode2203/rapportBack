<?php

namespace App\Controller\Api\rapports;

use App\Controller\Api\utils\BaseApiController;
use App\Dto\rapports\ActiviteCollectionDto;
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
            
            $rapportInsertArray = $this->cus->insertRapportDto($user, $dto);
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


}