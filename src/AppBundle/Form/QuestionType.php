<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('book', EntityType::class, [
            'class' => 'AppBundle\Entity\Book',
            'multiple' => true,
            'expanded' => true])
            ->add('amount', ChoiceType::class, [
                'choices' => [
                    5 => 5,
                    10 => 10,
                    15 => 15,
                    20 => 20,
                    25 => 25,
                    30 => 30,
                    35 => 35,
                    40 => 40
                ],
                'mapped' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\Question']);
    }

    public function getName()
    {
        return 'app_bundle_question_type';
    }
}
