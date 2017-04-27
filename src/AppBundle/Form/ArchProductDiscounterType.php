<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ArchProductDiscounterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => 'Name*'))
            ->add('is_active', ChoiceType::class, array(
            		'label' => false,
            		'choices' => array(
            				'No' => false,
            				'Yes' => true,
            		),
            		'attr' => array(
            				'class' => 'hidden'
            		)
            ))
       		->add('rate', NumberType::class, array(
       				'label' => 'Rate (%)*',
       		))
       		->add('suffix', NumberType::class, array(
       				'label' => 'Cents override',
       				'data' => 90
       		))
       		->add('filters', CollectionType::class, array(
       				'entry_type'    => ArchProductDiscounterFilterType::class,
       				'by_reference' => false,
       				'allow_add'     => true,
       				'allow_delete' => true,
       				'label' => ' ',
       		))
       		->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ArchProductDiscounter',
        ));
    }
}