<?php

namespace App\Form\Type;

use App\Enum\ArticleCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'article',
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Assert\File(
                        maxSize: '9M',
                        extensions: ['jpeg', 'jpg', 'png', 'webp'],
                        extensionsMessage: 'Veuillez télécharger une image au format JPEG, JPG, PNG ou WEBP.',
                    )
                ],
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix',
                'currency' => 'EUR',
                'required' => false,
            ])
            ->add('links', CollectionType::class, [
                'entry_type' => ArticleLinkType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
            ])
            ->add('category', EnumType::class, [
                'class' => ArticleCategory::class,
                'label' => 'Catégorie',
                'choice_label' => fn(ArticleCategory $choice) => $choice->label(),
                'label' => 'Catégorie',
            ])
        ;
    }
}
