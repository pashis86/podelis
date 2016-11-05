<?php

namespace AppBundle\Form;

use AppBundle\Entity\Question;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestQuestionType extends AbstractType
{
    private $em;

    /** @var EntityManager $em */
    public function __construct($em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Question $question */
        $question = $options['data']['question'];
        $answered = $options['data']['answered'];
        $id = $question->getId();

        $checkedAnswer = (array_key_exists($id, $answered) ? $answered[$id]: null);

        if($checkedAnswer != null){
            $checkedAnswer = $this->em->merge($checkedAnswer);
        }

        $builder->add('answers', EntityType::class, [
            'class' => 'AppBundle\Entity\Answer',
            'expanded' => true,
            'label' => $question->getContent(),
            'required' => false,
            'choices' => $question->getAnswers(),
            'placeholder' => false,
            'data' => $checkedAnswer])
        ->add('next', SubmitType::class)
        ->add('previous', SubmitType::class)
        ->add('submit', SubmitType::class);
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
