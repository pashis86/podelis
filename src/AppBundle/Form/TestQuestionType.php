<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestQuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('answers', EntityType::class, [
            'class' => 'AppBundle\Entity\Answer',
            'expanded' => true,
            'label' => $options['data']['question']->getContent(),
            'required' => false,
            'choices' => $options['data']['question']->getAnswers(),
            'placeholder' => false
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
     //   $resolver->setDefaults(['data_class' => 'AppBundle\Entity\Question']);
    }

    public function getName()
    {
        return 'app_bundle_test_question_type';
    }
}
