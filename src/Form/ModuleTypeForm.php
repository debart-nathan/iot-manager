<?php

namespace App\Form;

use App\Entity\Module;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModuleTypeForm extends AbstractType // Use the correct form class here
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $moduleTypes = $options['moduleTypes']; // Get the module types passed in options

        // Define the choices
        $choices = [];
        foreach ($moduleTypes as $moduleType) {
            $choices[$moduleType->getModuleTypeName()] = $moduleType->getModuleTypeName();
        }

        $builder
            // Add your other fields here...
            ->add('module_type_name', ChoiceType::class, [
                'choices'  => $choices,
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Module::class,
            'moduleTypes' => [], // add this line to accept moduleTypes in options
        ]);
    }
}