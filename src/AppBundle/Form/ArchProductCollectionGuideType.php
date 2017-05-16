<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArchProductCollectionGuideType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => 'Name*'))
            ->add('intro', TextareaType::class, array('label' => 'Intro', 'required' => false))
            ->add('steps', CollectionType::class, array(
            		'entry_type'    => ArchProductCollectionGuideStepType::class,
            		'by_reference' => false,
            		'allow_add'     => true,
            		'allow_delete' => true,
            		'label' => false,
            ))
       		->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ArchProductCollectionGuide',
        ));
    }
}