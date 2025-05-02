<?php

namespace App\Form\Client;

use App\Entity\Client\Client;
use App\Entity\Trainer\Trainer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('age', IntegerType::class)
            ->add('height', IntegerType::class)
            ->add('weight', NumberType::class, [
                'scale' => 1
            ])
            ->add('fatMass', NumberType::class, [
                'scale' => 1
            ])
            ->add('freeFatMass', NumberType::class, [
                'scale' => 1
            ])
            ->add('totalBodyWater', NumberType::class, [
                'scale' => 1
            ])
            ->add('trainer', EntityType::class, [
                'class' => Trainer::class,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => Client::class,
                'allow_extra_fields' => true
            ]);
    }
}
