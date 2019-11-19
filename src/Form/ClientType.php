<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', TextType::class, ['label'=>'Логин'])
            ->add('password', TextType::class, ['label'=>'Пароль'])
            ->add('firstname', TextType::class, ['label'=>'Имя'])
            ->add('lastname', TextType::class, ['label'=>'Фамилия'])
            ->add('sex', ChoiceType::class, [
                'choices'=>[
                    'Выберите...'=>0,
                    'Мужчина'=>1,
                    'Женьщина'=> 2
                ]
            ])
            ->add('email', TextType::class, ['label'=>'E-mail'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
