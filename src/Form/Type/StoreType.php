<?php

namespace App\Form\Type;

use App\Entity\Store;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StoreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la plateforme',
            ])
            ->add('searchUrlPattern', TextType::class, [
                'label' => 'URL de recherche (utiliser {query} pour le terme)',
                'help' => 'Exemple: https://www.amazon.fr/s?k={query}',
            ])
            ->add('regex', TextType::class, [
                'label' => 'Regex pour détecter la plateforme',
                'required' => false,
                'help' => 'Exemple: /amazon\\.fr/i',
            ])
            ->add('active', CheckboxType::class, [
                'label' => 'Plateforme active',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Store::class,
        ]);
    }
}
