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
     * This method handles the creation of a new module. It fetches all ModuleType entities,
     * creates a form for the new module, and renders the form view.
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

        return $this->render('Module/moduleForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
