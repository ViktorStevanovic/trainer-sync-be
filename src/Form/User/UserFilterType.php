<?php

namespace App\Form\User;

use App\Entity\UserType\UserType;
use App\Model\Form\UserFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('status', CheckboxType::class)
            ->add('userType', EntityType::class, [
                'class' => UserType::class,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => UserFilter::class,
                'allow_extra_fields' => false
            ]);
    }
}
