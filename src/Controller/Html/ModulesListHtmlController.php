<?php

namespace App\Controller\Html;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ModuleRepository;
use App\Repository\ModuleTypeRepository;
use App\Repository\ModuleTypeValueRepository;
use App\Repository\StatusRepository;
use App\Repository\ValueLogRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Request;

class ModulesListHtmlController extends AbstractController
{


    /**
     * @Route("/", name="modules")
     */
    public function getModules(
        Request $request,
        ModuleRepository $moduleRepository,
        StatusRepository $statusRepository,
        ModuleTypeRepository $moduleTypeRepository,
        ModuleTypeValueRepository $moduleTypeValueRepository,
        ValueLogRepository $valueLogRepository
    ): Response {
        // Fetch all GET parameters and sanitize them
        $filters = array_map('htmlspecialchars', $request->query->all());

        $sort = $request->get("sort", null);
        $order = $request->get("order", null);

        $modules = $moduleRepository
            ->findWithFiltersAndSort($filters, $sort, $order);

        $moduleData = [];
        foreach ($modules as $module) {
            // Get all ModuleTypeValues associated with the module type
            $moduleTypeName = $module->getModuleTypeName();

            // find all ModuleTypeValues with the same ModuleType as the current Module
            $moduleTypeValues = $moduleTypeValueRepository->findBy(['module_type_name' => $moduleTypeName]);
            $totalValueLogs = 0;
            $mostRecentValueLogs = [];

            // Iterate over the ModuleTypeValues
            foreach ($moduleTypeValues as $moduleTypeValue) {
                // Get ValueLogs associated with the ModuleTypeValue
                $valueLogs = $valueLogRepository
                    ->findBy(['module_type_value_id' => $moduleTypeValue, "module_id" => $module]);

                $totalValueLogs += count($valueLogs);

                // Set the default value to 'NAN'
                $mostRecentValueLog = ['data' => 'NAN'];
                $mostRecentValueLog['value_type_name'] = $moduleTypeValue->getValueTypeName()->getValueTypeName();
                $mostRecentValueLog['unit'] = $moduleTypeValue->getValueTypeName()->getUnit();
                // Find the most recent value log, if available
                if (!empty($valueLogs)) {
                    usort($valueLogs, function ($a, $b) {
                        return $a->getLogDate() < $b->getLogDate();
                    });
                    $mostRecentValueLog['data'] = $valueLogs[0]->getData();
                }

                $mostRecentValueLogs[$moduleTypeValue->getValueTypeName()->getValueTypeName()] = $mostRecentValueLog;
            }
            // Calculate how long the module has been active
            $now = new DateTime();
            $interval = $module->getActivationDate()->diff($now, true);

            // Explode the Module object into an array
            $moduleData[] = [
                'module_id' => $module->getModuleId(),
                'module_name' => $module->getModuleName(),
                'reference_code' => $module->getReferenceCode(),
                'model' => $module->getModel(),
                'module_type_name' => $moduleTypeName->getModuleTypeName(),
                'module_type_picture_file' => $moduleTypeName->getPictureFile(),
                'status_name' => $module->getStatusName()->getStatusName(),
                'status_category' => $module->getStatusName()->getCategory(),
                'status_message' => $module->getStatusMessage(),
                'total_value_logs' => $totalValueLogs,
                'most_recent_value_logs' => $mostRecentValueLogs,
                'activation_duration' => $interval->format('%a days %h hours'),
            ];
        }

        $moduleTypes = array_map(function ($item) {
            return $item['module_type_name'];
        }, $moduleTypeRepository->findDistinctModuleTypes());
        array_unshift($moduleTypes, "");

        $statusCategories = array_map(function ($item) {
            return $item['status_category'];
        }, $statusRepository->findDistinctStatusCategory());
        array_unshift($statusCategories, "");

        $statusNames = array_map(function ($item) {
            return $item['status_name'];
        }, $moduleRepository->findDistinctStatusInModules());
        array_unshift($statusNames, "");

        $filterData = [
            'module_type_name' => $moduleTypes,
            'sorts' => [
                "module_name",
                "reference_code",
                "model",
                "module_type_name",
                "status_name",
                "status_category",
                "log_date"
            ],
            'status_category' => $statusCategories,
            'status_name' => $statusNames,
        ];

        $label = [
            "module_name" => "nom du module",
            "reference_code" => "code de référence",
            "model" => "modèle",
            "module_type_name" => "nom du type de module",
            "status_name" => "nom de statut",
            "status_category" => "catégorie de statut",
            "status_category" => "gravité de status",
            "log_date" => "date d'envoi de valeur"
        ];



        return $this->render('Module/modules.html.twig', [
            'moduleData' => $moduleData,
            'filterData' => $filterData,
            'label' => $label,
        ]);
    }
}
