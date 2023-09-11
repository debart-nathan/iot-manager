<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use App\Entity\ModuleType;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ModuleTypeApiController extends AbstractController
{

    /**
     * @Route("/api/module-type/", name="get_description_and_picture", methods={"POST"})
     *
     * Handles a POST request to get the description and picture of a module type.
     *
     * @param Request $request The HTTP request.
     * @param ManagerRegistry $managerRegistry The manager registry.
     * @param LoggerInterface $logger The logger.
     *
     * @return JsonResponse The HTTP response.
     */
    public function getDescriptionAndPicture(
        Request $request,
        ManagerRegistry $managerRegistry,
        LoggerInterface $logger
    ): JsonResponse {
        $response = [];
        $statusCode = 200;

        try {
            $data = json_decode($request->getContent(), true);
            $moduleTypeName = $data['moduleTypeName'];

            // Validate moduleTypeName
            if (!is_string($moduleTypeName)) {
                $logger->info('Invalid module type name received: ' . $moduleTypeName);
                $response = ['error' => 'Invalid module type name'];
                $statusCode = 400;
            } else {
                // Fetch the ModuleType entity from the database
                $repository = $managerRegistry->getRepository(ModuleType::class);
                $moduleType = $repository->findOneBy(['module_type_name' => $moduleTypeName]);

                if (!$moduleType) {
                    // Log the error and set a generic error message
                    $logger->error('No ModuleType found for the name ' . $moduleTypeName);
                    $response = ['error' => 'Invalid module type name'];
                    $statusCode = 404;
                } else {
                    // Set the description and picture file of the ModuleType entity
                    $response = [
                        'description' => $moduleType->getDescription(),
                        'picture' => $moduleType->getPictureFile(),
                    ];
                    $logger->info('Successfully fetched ModuleType for name ' . $moduleTypeName);
                }
            }
        } catch (\Exception $exception) {
            // Log the exception message and set a generic error message
            $logger->error('Error: ' . $exception->getMessage());
            $response = ['error' => 'An error occurred, please try again later'];
            $statusCode = 500;
        }

        return new JsonResponse($response, $statusCode);
    }
}
