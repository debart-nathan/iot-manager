<?php

namespace App\Controller\Api;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use App\Entity\ValueLog;
use App\Entity\Module;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ValueLogApiController extends AbstractController
{
    /**
     * @Route("/api/value-log/module", methods={"POST"})
     *
     * Handles a POST request to log data of a module.
     *
     * @param Request $request The HTTP request object.
     * @param EntityManagerInterface $entityManager The entity manager interface instance.
     * @param LoggerInterface $logger The logger interface instance.
     *
     * @return JsonResponse Returns a JSON response with the formatted value log data if successful.
     *                      Returns a JSON response with an error message if unsuccessful.
     * */
    public function logDataOfModule(
        Request $request,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ): JsonResponse {
        // Parse the request body
        $data = json_decode($request->getContent(), true);
        $moduleId = $data['moduleId'];

        // Fetch the module entity from the database
        $module = $entityManager->getRepository(Module::class)->find($moduleId);

        // Check if the module exists
        if (!$module) {
            $logger->error('Failed to fetch the module entity.');
            return $this->json(['error' => 'Module not found.'], 404);
        }

        // Fetch the necessary ValueLog data from the database based on the moduleId
        $repository = $entityManager->getRepository(ValueLog::class);
        $valueLogs = $repository->findBy(['module_id' => $moduleId]);

        // Format the data
        try {

            $values = [];
            foreach ($valueLogs as $valueLog) {
                $valueType = $valueLog->getModuleTypeValueId()->getValueTypeName();
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

                $logDateStr = $logDate->format('Y-m-d H:i:s');
                $values[$valueTypeName]['valueLog'][$logDateStr] = $data;
            }
        } catch (\Exception $e) {
            $logger->error('Failed to format the data.');
            return $this->json(['error' => 'Failed to format the data.'], 500);
        }

        // Log success
        $logger->info('Successfully fetched and formatted value log data.');


        // Return the JsonResponse object
        return new JsonResponse($values);
    }
}
