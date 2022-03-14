<?php

namespace App\Form;

use App\Entity\Article;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre'
            ])
            ->add('shortdescrition', TextType::class, [
                'label' => 'Description courte'
            ])
            ->add('thumbnail', FileType::class, [
                'label' => 'Votre image',
                'data_class' => null,
                'required' => false,
                'mapped' => false
            ])
            ->add('content', CKEditorType::class, [
                'label' => 'Votre Article',
                'constraints' => [
                    new Length([
                        'min' => 40,
                        'max' => 1500,
                    ]),
                ],
            ])
            ->add('draft', ChoiceType::class, [
                'label' => 'Est-ce un brouillon ?',
                'choices' => [
                    'Oui'=> false,
                    'Non' => true
                ]
            ])
            ->add('Publier', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
