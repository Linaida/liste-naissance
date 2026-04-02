<?php

namespace App\Form\Type;

use App\Entity\Pregnancy;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PregnancyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDate', DateType::class, [
                'label' => 'Date de début de grossesse',
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('endDate', DateType::class, [
                'label' => 'Date prévue d\'accouchement',
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('babyGender', ChoiceType::class, [
                'label' => 'Sexe du bébé',
                'choices' => [
                    'Non déterminé' => null,
                    'Fille 👧' => 'girl',
                    'Garçon 👦' => 'boy',
                ],
                'required' => false,
                'placeholder' => 'Sélectionner...',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pregnancy::class,
        ]);
    }
}
