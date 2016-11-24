<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('oldPassword', PasswordType::class,[
            'constraints' => [
                new UserPassword(['message' => 'Neteisingas slaptažodis'])
            ]
        ])
            ->add('newPassword', RepeatedType::class,[
                'type' => PasswordType::class,
                'constraints' => [
                    new NotBlank(['message' => 'Iveskite slaptazodi']),
                    new Length(['min' => 6,
                                'max' => 32,
                                'minMessage' => 'Slaptazodi turi sudaryti bent 6 symboliai',
                                'maxMessage' => 'Slaptazodis per ilgas'])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
       // $resolver->setDefaults(['data_class' => 'AppBundle\Entity\User']);
    }

    public function getName()
    {
        return 'app_bundle_change_password_type';
    }
}
