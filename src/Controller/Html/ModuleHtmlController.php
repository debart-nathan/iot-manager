<?php
namespace App\Controller\Html;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Module;
use App\Form\ModuleTypeForm;
use App\Form\ModuleForm;
use App\Repository\ModuleTypeRepository;
use Symfony\Component\HttpFoundation\Request;

class ModuleHtmlController extends AbstractController
{

    /**
     * @Route("/module/new", name="module_new")
     */
    public function new(Request $request,ModuleTypeRepository $moduleTypeRepository): Response
    {
        $module = new Module();
        // Fetch all ModuleType entities
        $moduleTypes = $moduleTypeRepository->findAll();

        // Pass them to the moduleTypeForm
        $moduleTypeForm = $this->createForm(ModuleTypeForm::class, $module, [
            'moduleTypes' => $moduleTypes,
        ]);

        // Get the rest of the fields
        $moduleForm = $this->createForm(ModuleForm::class, $module);

        $moduleTypeForm->handleRequest($request);

        if ($moduleTypeForm->isSubmitted() && $moduleTypeForm->isValid()) {
            return $this->redirectToRoute('module_show', ['id' => $module->getModuleId()]);
        }

        return $this->render('Module/moduleForm.html.twig', [
            'module' => $module,
            'moduleTypeForm' => $moduleTypeForm->createView(),
            'moduleForm' => $moduleForm->createView(),
        ]);
    }
}