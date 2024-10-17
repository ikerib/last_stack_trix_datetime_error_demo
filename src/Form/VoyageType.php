<?php

namespace App\Form;

use App\Entity\Planet;
use App\Entity\Voyage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfonycasts\DynamicForms\DependentField;
use Symfonycasts\DynamicForms\DynamicFormBuilder;

class VoyageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder = new DynamicFormBuilder($builder);
        $builder
            ->add('purpose')
            ->add('leaveAt', DateType::class, [
                'widget' => 'single_text',
                'attr' => [
                    'data-controller' => 'datepicker',
                ]
            ])
            ->add('startDate', DateType::class, [
                'label' => 'Hasi',
                'widget' => 'single_text',
                'help' => 'Noiz hasten da eskaintza.',
                'help_attr' => [
                    'class' => 'mt-0important text-sm text-gray-500 dark:text-gray-400',
                ],
                'attr' => [
                    'data-controller' => 'datepicker',
                    'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 pl-10 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                ],
                'label_attr' => [
                ],
            ])
            ->add('description', HiddenType::class, [
                'label' => 'Deskribapena:',
                'help' => 'Hemen eskaintzari buruzko informazio areagotua zehaztu daiteke',
                'help_attr' => [
                    'class' => 'mt-0important text-sm text-gray-500 dark:text-gray-400',
                ],
                'attr' => [
                    'class' => 'stimulus-trix',
                    'data-controller' => 'trix',
                ],
            ])
            ->add('planet', null, [
                'choice_label' => 'name',
                'placeholder' => 'Choose a planet',
                //'autocomplete' => true,
            ])
            ->addDependent('wormholeUpgrade', ['planet'], function (DependentField $field, ?Planet $planet) {
                if (!$planet || $planet->isInMilkyWay()) {
                    return;
                }

                $field->add(ChoiceType::class, [
                    'choices' => [
                        'Yes' => true,
                        'No' => false,
                    ],
                ]);
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voyage::class,
        ]);
    }
}
