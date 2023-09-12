<?php

namespace App\Controller\Html;

use App\Entity\Module;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
     * 
     * Handles the retrieval and rendering of modules. It uses the request object to fetch and sanitize parameters, 
     * retrieves modules based on these parameters, processes the retrieved modules, and finally renders the template.
     *
     * @param Request $request The request object.
     * @return Response The rendered template.
     */
    public function getModules(
        Request $request,
        ModuleRepository $moduleRepository,
        ModuleTypeRepository $moduleTypeRepository,
        ModuleTypeValueRepository $moduleTypeValueRepository,
        StatusRepository $statusRepository,
        ValueLogRepository $valueLogRepository
    ) {
        $parameters = $this->fetchAndSanitizeParameters($request);
        $modules = $this->retrieveModules($moduleRepository, $parameters);
        $processedModules = $this->processModules($modules, $moduleTypeValueRepository, $valueLogRepository);
        $filterData = $this->retrieveFilterData($moduleTypeRepository, $statusRepository, $moduleRepository);
        return $this->renderTemplate($processedModules, $filterData);
    }


    /**
     * Fetches all GET parameters from the request object and sanitizes them to prevent XSS attacks.
     *
     * @param Request $request The request object.
     * @return array An array containing sanitized filters, sort, and order parameters.
     */
    private function fetchAndSanitizeParameters(Request $request)
    {
        // Fetch all GET parameters and sanitize them
        $filters = array_map('htmlspecialchars', $request->query->all());

        $sort = $request->get("sort", null);
        $order = $request->get("order", null);

        return [$filters, $sort, $order];
    }

    /**
     * Retrieves modules from the repository based on the provided filters, sort, and order parameters.
     *
     * @param ModuleRepository $moduleRepository The module repository.
     * @param array $parameters An array containing filters, sort, and order parameters.
     * @return array The retrieved modules.
     */
    private function retrieveModules(ModuleRepository $moduleRepository, $parameters)
    {
        [$filters, $sort, $order] = $parameters;

        // Retrieve modules from the repository
        $modules = $moduleRepository->findWithFiltersAndSort($filters, $sort, $order);
        if ($modules === null) {
            throw new HttpException(500, 'Failed to retrieve modules from repository');
        }

        return $modules;
    }


    /**
     * Processes the retrieved modules by retrieving associated module type values and value logs,
     * calculating module activation duration, and organizing the data into an array.
     *
     * @param array $modules The modules to process.
     * @param ...$parameters The additional parameters used in processing.
     * @return array The processed modules.
     */
    private function processModules(
        array $modules,
        ModuleTypeValueRepository $moduleTypeValueRepository,
        ValueLogRepository $valueLogRepository
    ) {
        $moduleData = [];
        foreach ($modules as $module) {
            // Get all ModuleTypeValues associated with the module type
            $moduleTypeName = $module->getModuleTypeName();

            // Find all ModuleTypeValues with the same ModuleType as the current Module
            $moduleTypeValues = $moduleTypeValueRepository->findBy(['module_type_name' => $moduleTypeName]);
            if ($moduleTypeValues === null) {
                throw new HttpException(500, 'Failed to retrieve module type from repository');
            }

            $totalValueLogs = 0;
            $mostRecentValueLogs = [];

            // Iterate over the ModuleTypeValues
            foreach ($moduleTypeValues as $moduleTypeValue) {
                // Get ValueLogs associated with the ModuleTypeValue
                $valueLogs = $valueLogRepository->findBy([
                    'module_type_value_id' => $moduleTypeValue,
                    "module_id" => $module
                ]);

                if ($valueLogs === null) {
                    throw new HttpException(500, 'Failed to retrieve values log from repository');
                }

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

        return $moduleData;
    }


    /**
     * Retrieves filter data for the template, such as distinct module types, status categories, and status names.
     *
     * @param ModuleTypeRepository $moduleTypeRepository The repository object used to retrieve module types.
     * @param ...$parameters The additional parameters used in retrieving filter data.
     * @return array The retrieved filter data.
     */
    private function retrieveFilterData(
        ModuleTypeRepository $moduleTypeRepository,
        StatusRepository $statusRepository,
        ModuleRepository $moduleRepository
    ) {
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

        return $filterData;
    }

    /**
     * Renders the final template with the processed modules and filter data.
     *
     * @param array $processedModules The processed modules to render in the template.
     * @param array $filterData The filter data to render in the template.
     * @return Response The rendered template.
     */
    private function renderTemplate($processedModules, $filterData)
    {
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
            'moduleData' => $processedModules,
            'filterData' => $filterData,
            'label' => $label,
        ]);
    }
}
