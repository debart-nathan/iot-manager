<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use App\Repository\StatusRepository;
use App\Entity\Module;


class ModuleApiController extends AbstractController
{

    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/api/module/new", name="api_module_new", methods={"POST"})
     */
    public function new(
        Request $request,
        StatusRepository $statusRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $module = new Module();



        // Fetch the 'ok' status as an object
        $status = $statusRepository->findOneBy(['status_name' => 'Normal']);

        // Check if the status is null and return a 404 response

        if (!$status) {

            $this->logger->error('Error during module creation : could not find the "ok" status in the database.');

            $this->addFlash(
                'error',
                'Nous sommes désolés, mais la fonctionnalité de création de module est temporairement indisponible.' .
                    'Ceci est dû à un problème technique avec notre base de données.' .
                    'Si ce problème persiste, veuillez nous contacter.'
            );


            return $this->redirectToRoute("module_new");
        }



        // Set properties of the module with default values
        $module->setStatus($status);
        $module->setStatusMessage(null);
        $module->setActivationDate(new \DateTime());

        // Set properties of the module based on the request

        $entityManager->persist($module);
        $entityManager->flush();

        // Redirect to the "/modules" route for success
        return $this->redirectToRoute('modules');
    }
}
