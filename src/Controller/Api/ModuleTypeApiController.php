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
     */
    public function getDescriptionAndPicture(
        Request $request,
        ManagerRegistry $managerRegistry,
        LoggerInterface $logger
    ): JsonResponse {
        try {
            $data = json_decode($request->getContent(), true);
            $moduleTypeName = $data['moduleTypeName'];
            // Fetch the ModuleType entity from the database
            $repository = $managerRegistry->getRepository(ModuleType::class);
            $moduleType = $repository->findOneBy(['module_type_name' => $moduleTypeName]);

            if (!$moduleType) {
                throw new Exception("No ModuleType found for the name $moduleTypeName");
            }

            // Return the description and picture file of the ModuleType entity
            return new JsonResponse([
                'description' => $moduleType->getDescription(),
                'picture' => $moduleType->getPictureFile(),
            ]);
        } catch (\Exception $exception) {
            // Log the exception message
            $logger->error('Error: ' . $exception->getMessage());
            return new JsonResponse([
                'error' => $exception->getMessage(),
            ], 500);
        }
    }
}
