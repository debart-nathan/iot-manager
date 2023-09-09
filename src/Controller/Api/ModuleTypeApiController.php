<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;

class ModuleTypeApiController extends AbstractController
{

    /**
     * @Route("/api/get/module-type/full-description", name="get_full_description", methods={"POST"})
     */
    public function getFullDescription(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        // Retrieve the "moduleType" value from the request.
        $moduleType = $request->get('moduleTypeName');

        // Fetch the ModuleType entity from the database.
        $moduleTypeEntity = $doctrine->getRepository(ModuleType::class)->find($moduleType);

        // Retrieve ModuleTypeValue entities related to the fetched ModuleType.
        $moduleTypeValues = $doctrine->getRepository(ModuleTypeValue::class)
            ->findBy(['moduleType' => $moduleTypeEntity]);

        // Initialize an empty array.
        $valueTypesData = [];

        // Iterate over each ModuleTypeValue entity.
        foreach ($moduleTypeValues as $moduleTypeValue) {
            // Fetch the related ValueType entity.
            $valueType = $doctrine->getRepository(ValueType::class)->find($moduleTypeValue->getValueType());

            // Add relevant data from the ValueType entity to the "valueTypesData" array.
            $valueTypesData[] = [
                'id' => $valueType->getId(),
                'name' => $valueType->getName(),
                'description' => $valueType->getDescription(),
            ];
        }

        // Return a JSON response containing the ModuleType description and the ValueType related data.
        return $this->json([
            'description' => $moduleTypeEntity->getDescription(),
            'valueTypes' => $valueTypesData,
        ]);
    }
}
