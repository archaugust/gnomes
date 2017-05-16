<?php
namespace CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArchPageHomeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => 'Name*'))
            ->add('slides', CollectionType::class, array(
            		'entry_type'    => ArchPageHomeSlideType::class,
            		'by_reference' => false,
            		'allow_add'     => true,
            		'allow_delete' => true,
            		'label' => ' ',
            ))
            ->add('sections', CollectionType::class, array(
            		'entry_type'    => ArchPageHomeSectionType::class,
            		'by_reference' => false,
            		'allow_add'     => true,
            		'allow_delete' => true,
            		'label' => ' ',
            ))
            ->add('meta_title', TextType::class, array(
       				'label' => 'Meta Title',
      				'required' => false
       		))
       		->add('meta_description', TextareaType::class, array(
       				'label' => 'Meta Description',
       				'required' => false
       		))
       		->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CmsBundle\Entity\ArchPageHome',
        	'allow_extra_fields' => true
        ));
    }
}