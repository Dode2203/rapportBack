<?php

namespace App\Controller\Api\rapports;

use App\Controller\Api\utils\BaseApiController;
use App\Dto\rapports\CalendrierDto;
use App\Service\rapports\CalendriersService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Annotation\TokenRequired;

#[Route('/calendriers')]
class CalendriersController extends BaseApiController
{
    private CalendriersService $service;

    public function __construct(CalendriersService $service)
    {
        $this->service = $service;
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

}