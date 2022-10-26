<?php

namespace App\Form;

use App\Entity\Carte;

use App\Entity\Classe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AddCardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom de la carte'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ajouter un nom.',
                    ]),
                ],
            ])

            ->add('imagePerso', FileType::class,[
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '200k',
                        'mimeTypes' => [
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Télécharger une image JPG valide !',
                    ])
                ],
            ])

            ->add('attaque', NumberType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ajouter des points d\'attaque.',
                    ]),
                ],
            ])

            ->add('defense', NumberType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ajouter des points de défense.',
                    ]),
                ],
            ])

            ->add('description', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control' ,
                    'placeholder' => 'Description de la carte'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ajouter une description.',
                    ]),
                ],
            ])

            ->add('prix', NumberType::class, [
                'label' => 'Prix de la carte',
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ajouter un prix.',
                    ]),
                ],
            ])

            ->add('classe', ChoiceType::class, [
                'label' => false,
                'choices' => $options['cl'],
                'attr' => [
                    'class' => 'form-control',
                ],
                'choice_label' => function(?Classe $cl) {
                    return $cl ? $cl->getNom() : '';
                },
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ajouter une classe.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Carte::class,
            'cl' => false
        ]);
    }
}
