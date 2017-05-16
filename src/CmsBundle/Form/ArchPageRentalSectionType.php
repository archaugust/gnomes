<?php
namespace CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArchPageRentalSectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('name', TextType::class, array('label' => 'Title', 'required' => false))
	        ->add('standard_image', FileType::class, array(
	        		'data_class' => null,
	        		'label' => 'Standard - Image',
	        		'required' => false
	        ))
	        ->add('standard_content', TextareaType::class, array(
	        		'label' => 'Standard - Content',
	        		'required' => false
	        ))
	        ->add('standard_price', TextType::class, array('label' => 'Standard - Price', 'required' => false))
	        ->add('performance_image', FileType::class, array(
	        		'data_class' => null,
	        		'label' => 'Performance - Image',
	        		'required' => false
	        ))
	        ->add('performance_content', TextareaType::class, array(
	        		'label' => 'Performance - Content',
	        		'required' => false
	        ))
	        ->add('performance_price', TextType::class, array('label' => 'Performance - Price', 'required' => false))
	        ->add('demo_image', FileType::class, array(
	        		'data_class' => null,
	        		'label' => 'Demo - Image',
	        		'required' => false
	        ))
	        ->add('demo_content', TextareaType::class, array(
	        		'label' => 'Demo - Content',
	        		'required' => false
	        ))
	        ->add('demo_price', TextType::class, array('label' => 'Demo - Price', 'required' => false))
	        ->add('touring_image', FileType::class, array(
	        		'data_class' => null,
	        		'label' => 'Touring - Image',
	        		'required' => false
	        ))
	        ->add('touring_content', TextareaType::class, array(
	        		'label' => 'Touring - Content',
	        		'required' => false
	        ))
	        ->add('touring_price', TextType::class, array('label' => 'Touring - Price', 'required' => false))
	        
	    ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CmsBundle\Entity\ArchPageRentalSection',
        ));
    }
}