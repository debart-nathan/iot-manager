<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ModuleTypeRepository;
use App\Repository\ModuleRepository;
use Psr\Log\LoggerInterface;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Module;
use App\Form\ModuleForm;
use App\Repository\ValueLogRepository;

class ModuleApiController extends AbstractController
{
    /**
     * @Route("/api/module/new", name="api_module_new", methods={"POST"})
     */
    public function newModule(
        Request $request,
        StatusRepository $statusRepository,
        ModuleTypeRepository $moduleTypeRepository,
        LoggerInterface $logger,
        EntityManagerInterface $entityManager
    ) {
        try {
            $newModule = new Module();
            $moduleTypes = $moduleTypeRepository->findAll();

            $form = $this->createForm(ModuleForm::class, $newModule, [
                'moduleTypes' => $moduleTypes,
                'action' => $this->generateUrl('api_module_new'),
                'method' => 'POST'
            ]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // Set properties for the new Module object
                $newModule->setModuleName($form->get('module_name')->getData());
                $newModule->setReferenceCode($form->get('reference_code')->getData());
                $newModule->setModel($form->get('model')->getData());
                // Set the current date and time
                $newModule->setActivationDate(new \DateTime());

                // Fetch 'ModuleType' object from database and set module type
                $moduleType = $moduleTypeRepository->findOneBy(
                    ['module_type_name' => $form->get('module_type_name')->getData()]
                );
                $newModule->setModuleTypeName($moduleType);

                // Fetch 'Status' object from database and set status
                $status = $statusRepository->findOneBy(['status_name' => 'Normal']);
                $newModule->setStatusName($status);
                $newModule->setStatusMessage('');

                // Save the new Module object to the database
                $entityManager->persist($newModule);
                $entityManager->flush();

                return $this->redirectToRoute('modules');
            } else {
                return new Response('Form not submitted or not valid', 400);
            }
        } catch (\Exception $e) {
            // log the exception
            $logger->error($e->getMessage());
            throw $e;
        }
    }

    /**
     * @Route("/api/module/remove/{id}", name="api_module_remove", methods={"POST"})
     */
    public function removeModule(
        int $id,
        Request $request,
        ModuleRepository $moduleRepository,
        ValueLogRepository $valueLogRepository,
        EntityManagerInterface $entityManager
    ) {
        $module = $moduleRepository->find($id);

        if (!$module) {
            throw $this->createNotFoundException('No module found for id ' . $id);
        }

        if (
            $this->isCsrfTokenValid('delete' . $module->getModuleId(), 
            $request->request->get('_token'))
        ) {
            $valueLogs = $valueLogRepository->findBy(['module_id' => $module->getModuleId()]);
            foreach ($valueLogs as $valueLog) {
                $entityManager->remove($valueLog);
            }
            $entityManager->remove($module);
            $entityManager->flush();
            $this->addFlash('success', 'Module has been deleted!');

            return $this->redirectToRoute("modules");
        }

        // If CSRF token is not valid, do nothing and return
        return new Response('Invalid CSRF token', 400);
    }
}
