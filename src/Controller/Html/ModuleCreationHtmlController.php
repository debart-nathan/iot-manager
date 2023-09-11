<?php

namespace App\Controller\Html;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Module;
use App\Form\ModuleForm;
use App\Repository\ModuleTypeRepository;

use Symfony\Component\HttpFoundation\Request;

class ModuleCreationHtmlController extends AbstractController
{

    /**
     * @Route("/module/new", name="module_new")
     */
    public function new(Request $request, ModuleTypeRepository $moduleTypeRepository): Response
    {
        $module = new Module();
        // Fetch all ModuleType entities
        $moduleTypes = $moduleTypeRepository->findAll();

        // Create the form
        $form = $this->createForm(ModuleForm::class, $module, [
            'moduleTypes' => $moduleTypes,
            'action' => $this->generateUrl('api_module_new'),
            'method' => 'POST'
        ]);

        // Handle the request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Perform some business logic...

            return $this->redirectToRoute('module_success');
        }

        return $this->render('Module/moduleForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
