<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Task;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Polyfill\Intl\Idn\Resources\unidata\Regex;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name:',
                'attr' => [
                    'placeholder' => 'Enter the name',
                    'class' => 'form-control', 
                ],
                'constraints' => [
                    new Length(
                        min: 4,
                        max: 20,
                        minMessage: 'Your name must be at least {{ limit }} characters long',
                        maxMessage: 'Your name cannot be longer than {{ limit }} characters'
                    ),
                    new \Symfony\Component\Validator\Constraints\Regex(
                        pattern: "/[A-Za-z]*/",
                        message: 'Your name cannot contain a number'

                    ),
                    new NotBlank(
                        message: 'Name cannot be blank'
                    )
                ]
                
            ])
            ->add('description', TextType::class, [
                'label' => 'Description:',
                'attr' => [
                    'placeholder' => 'Enrer description',
                    'class' => 'form-control',
                ],'constraints' => [
                    new Length(
                        min: 5,
                        max: 100,
                        minMessage: 'Your name must be at least {{ limit }} characters long',
                        maxMessage: 'Your name cannot be longer than {{ limit }} characters'
                    )
                ]
                
            ])
            ->add('data', DateType::class, [
                'placeholder' => [
                    'year' => '2023', 'month' => 'September', 'day' => '30',
                ],
                
            ])
            ->add('createdAt', DateTimeType::class, [
                'date_label' => 'Start at:',
                
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => 'Choose category',
                'attr' => [
                    'class' => 'form-control', 
                ],
                
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => [
                    'class' => 'btn btn-primary', 
                ],
            ]);
    }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
