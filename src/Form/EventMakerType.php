<?php

namespace App\Form;

use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use \Symfony\Component\Validator\Constraints\Image as Image;

class EventMakerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minLength' => '2',
                    'maxLength' => "150" 
                ],
                'label' => 'Nom de l\'evenement',
                'label_attr' => [
                    'class' => 'form_label m-1'
                ],
                'constraints' => [
                    new Assert\NotBlank(),                        
                    new Assert\Length([
                        'min' => 2, 'max' => 50,
                        'minMessage' => 'le nom de l\'evenement doit contenir au moins 2 caractères',
                        'maxMessage' => 'le nom de l\'evenement doit contenir moins de 150 caractères',
                    ]),
                ]
            ])
            ->add('description', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Description de l\'evenement',
                'label_attr' => [
                    'class' => 'form_label m-1'
                ],
                'constraints' => [
                    new Assert\NotBlank(),                        
                    ],
                ])
            ->add('date_debut', DateTimeType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Date de debut',
                'label_attr' => [
                    'class' => 'form_label m-1'
                ],
                'widget' => 'single_text', 
                'html5' => true
            ])
            ->add('date_fin', DateTimeType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Date de fin',
                'label_attr' => [
                    'class' => 'form_label m-1'
                ],
                'widget' => 'single_text', 
                'html5' => true
            ])
            ->add('lieux', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minLength' => '2',
                    'maxLength' => "150" 
                ],
                'label' => 'Lieu de l\'evenement',
                'label_attr' => [
                    'class' => 'form_label m-1'
                ],
                'constraints' => [
                    new Assert\NotBlank(),                        
                    new Assert\Length([
                        'min' => 2, 'max' => 50,
                        'minMessage' => 'Le lieu de l\'evenement doit contenir au moins 2 caractères',
                        'maxMessage' => 'le lieu de l\'evenement doit contenir moins de 150 caractères',
                    ]),
                ]
            ])
            ->add('image', FileType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'formFile',
                ],
                'label' => 'Importer une image',
                'label_attr' => [
                    'class' => 'form_label m-1'
                ],
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '5M', // Taille maximale de l'image
                    ]),
                ],
            ])
            ->add('id_createur', HiddenType::class)
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mb-2' 
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
