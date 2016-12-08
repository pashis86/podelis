<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('book', EntityType::class, ['class' => 'AppBundle\Entity\Book'
            ])
          ->add('title', TextType::class)
          ->add('content', TextareaType::class)
          ->add('explanation', TextareaType::class)
          ->add('answers', CollectionType::class, [
              'entry_type' => AnswerType::class,
              'allow_add' => true,
              'allow_delete' => true,
              'by_reference' => false,
              'constraints' => new Count([
                  'min' => 4,
                  'max' => 7
              ])
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
