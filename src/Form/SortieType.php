<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('dateDebut',\Symfony\Component\Form\Extension\Core\Type\DateTimeType::class,[
                'html5' => true,
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'data' => new \DateTime('tomorrow'),
                'invalid_message' => 'La date ne peut pas être dans le passé ou aujourd\'hui.',
                'constraints' => [
                    new Callback(function ($object, ExecutionContextInterface $context) {
                        if ($object < new \DateTime('today')) {
                            $context->addViolation('La date ne peut pas être dans le passé.');
                        }
                    }),
                ],

            ])
            ->add('duree')
            ->add('dateCloture',DateType::class,[
                'html5'=>true,
                'widget'=>'single_text',
                'constraints' => [
                    new LessThan([
                        'value' => 'today',
                        'message' => 'La date de clôture doit être inférieure à la date de début.',
                    ]),
                ],
            ])
            ->add('descriptioninfos')
            ->add('lieu',null,["choice_label"=>"nom"])
            ->add('nbInscriptionsMax')
            ->add('urlPhoto')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
