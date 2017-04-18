<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ArchProductTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => 'Name*'))
            ->add('is_active', ChoiceType::class, array(
            		'label' => 'Active',
            		'choices' => array(
            				'Yes' => true,
            				'No' => false
            		),
            ))
            ->add('image', FileType::class, array(
            		'data_class' => null,
            		'required' => false,
            		'label' => 'Image',
            ))
            ->add('collection', EntityType::class,
            		array(
            				'class' => 'AppBundle:ArchProductCollection',
            				'choice_label' => 'name',
            				'choice_value' => 'id',
            				'placeholder' => 'Select Collection',
            				'required' => false
            		)
            )
            ->add('meta_title', TextType::class, array(
            		'label' => 'Meta Title',
            		'required' => false
            ))
            ->add('meta_description', TextType::class, array(
            		'label' => 'Meta Description',
            		'required' => false
            ))
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ArchProductType',
        ));
    }
}