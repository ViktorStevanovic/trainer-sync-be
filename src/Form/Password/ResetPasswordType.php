<?php

namespace App\Form\Password;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/** * Form da utilizzare per il ripristino della password tramite link temporaneo * e come base per il cambio password per l'utente loggato */
class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('newPassword', RepeatedPasswordType::class, [
            'user' => $options['user'],
        ]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('user');
    }
}
