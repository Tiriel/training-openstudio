<?php

namespace App\Form\Type;

use App\Dto\Registration;
use Symfony\Component\Form\Flow\AbstractFlowType;
use Symfony\Component\Form\Flow\FormFlowBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractFlowType
{
    public function buildFormFlow(FormFlowBuilderInterface $builder, array $options): void
    {
        $builder
            ->addStep('account', AccountFormType::class)
            ->addStep('profile', ProfileType::class)
            ->addStep('confirmation', ConfirmationType::class)
        ;

        $builder->add('nav', RegistrationNavigatorType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => Registration::class,
                'step_property_path' => 'currentStep'
            ]);
    }
}
