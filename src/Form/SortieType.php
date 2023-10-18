<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('dateDebut', \Symfony\Component\Form\Extension\Core\Type\DateTimeType::class, [
                'html5' => true,
                'date_widget' => 'single_text',
                'time_widget' => 'single_text'
            ])
            ->add('duree')
            ->add('dateCloture', DateType::class, [
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('descriptioninfos')
            ->add('lieu', null, ["choice_label" => "nom"])
            ->add('nbInscriptionsMax')
            ->add('urlPhoto', FileType::class, [ 
                'label' => 'Image de la sortie',
                'required' => false, 
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
