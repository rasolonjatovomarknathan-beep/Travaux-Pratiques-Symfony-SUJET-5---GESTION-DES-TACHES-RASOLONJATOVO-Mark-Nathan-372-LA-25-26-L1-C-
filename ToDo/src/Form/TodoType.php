<?php

namespace App\Form;

use App\Entity\Todo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TodoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('description', TextareaType::class, [
                'required' => false,
            ])
            ->add('priority', ChoiceType::class, [
                'choices' => [
                    'LOW' => 'LOW',
                    'MEDIUM' => 'MEDIUM',
                    'HIGH' => 'HIGH',
                ],
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'TODO' => 'TODO',
                    'IN_PROGRESS' => 'IN_PROGRESS',
                    'DONE' => 'DONE',
                ],
            ])
            ->add('dueDate', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('assigneeUsername', TextType::class, [
                'required' => false,
                'label' => "Utilisateur (username)",
                'empty_data' => null,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Todo::class,
        ]);
    }
}
