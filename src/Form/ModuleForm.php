<?php

namespace App\Form;

use App\Entity\Module;
use App\Entity\ModuleType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModuleForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $moduleTypes = $options['moduleTypes']; // Get the module types passed in options

        // Define the choices
        $choices = [];
        foreach ($moduleTypes as $moduleType) {
            $choices[$moduleType->getModuleTypeName()] = $moduleType;
        }

        $builder
            ->add('module_name', TextType::class)
            ->add('reference_code', TextType::class)
            ->add('model', TextType::class)
            ->add('module_type_name', EntityType::class, [
                'class' => ModuleType::class,
                'choice_label' => function (ModuleType $moduleType) {
                    return $moduleType->getModuleTypeName();
                },
                'choices' => $choices,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Module::class,
            'moduleTypes' => [], // add this line to accept moduleTypes in options
        ]);
    }
}
