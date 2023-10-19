<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

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
            ->add('dateCloture', DateType::class, [
                'html5' => true,
                'widget' => 'single_text',
                'constraints' => [
                    new Callback([$this, 'validateDateCloture']),
                ],
            ])
            ->add('descriptioninfos')
            ->add('lieu', null, [
                'label' => 'Ville',
                'choice_label' => 'ville.nom'
            ])
            ->add('nbInscriptionsMax')
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image',
                'help' => 'Formats autorisés (JPEG, JPG, PNG) | Poids max. (5 Mo)',
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
                'asset_helper' => true]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }

    public function validateDateCloture($dateCloture, ExecutionContextInterface $context)
    {
        $dateDebut = $context->getRoot()->getData()->getDateDebut();
        $today = new \DateTime('today');

        if ($dateCloture < $today) {
            $context->buildViolation('La date de clôture ne peut pas être dans le passé.')
                ->atPath('dateCloture')
                ->addViolation();
        }

        if ($dateCloture >= $dateDebut) {
            $context->buildViolation('La date de clôture doit être antérieure à la date de début.')
                ->atPath('dateCloture')
                ->addViolation();
        }
    }
}
