<?php

namespace App\Controller\Api;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ValueLog;
use App\Entity\ValueType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ValueLogApiController extends AbstractController
{
    /**
     * @Route("/api/value-log/module", methods={"POST"})
     */
    public function logDataOfModule(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Parse the request body
        $data = json_decode($request->getContent(), true);
        $moduleId = $data['moduleId'];

        // Fetch the necessary ValueLog data from the database based on the moduleId
        $repository = $entityManager->getRepository(ValueLog::class);
        $valueLogs = $repository->findBy(['module_id' => $moduleId]);

        // Format the data
        $values = [];
        foreach ($valueLogs as $valueLog) {
            $valueType = $valueLog->getModuleTypeValue()->getValueType();
            $valueTypeName = $valueType->getValueTypeName();
            $unit = $valueType->getUnit();
            $logDate = $valueLog->getLogDate();
            $data = $valueLog->getData();

            if (!isset($values[$valueTypeName])) {
                $values[$valueTypeName] = [
                    'unit' => $unit,
                    'valueLog' => [],
                ];
            }

            $values[$valueTypeName]['valueLog'][$logDate] = $data;
        }

        // Return the JsonResponse object
        return new JsonResponse($values);
    }
}
