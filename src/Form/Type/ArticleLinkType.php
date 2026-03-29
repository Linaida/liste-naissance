<?php
namespace App\Form\Type;

use App\Entity\ArticleLink;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleLinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url', UrlType::class, [
                'label' => 'URL',
                'attr' => [
                    'data-store-detector-target' => 'url',
                    'class' => 'store-detector-url',
                    'data-action' => 'input->store-detector#detect'
                ]
            ])
            ->add('label', TextType::class, [
                'label' => 'Enseigne',                
                'attr' => [
                    'data-store-detector-target' => 'label',
                    'class' => 'store-detector-label',
                    ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ArticleLink::class,
        ]);
    }
}
