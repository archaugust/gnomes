<?php
namespace CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ArchPageHomeSlideType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('banner_text', TextareaType::class, array('label' => 'Banner Text', 'required' => false))
	        ->add('banner_text_color', ChoiceType::class, array(
		        	'label' => 'Banner Text Colour',
	        		'choices' => array(
	        				'Black' => true,
	        				'White' => false,
	        		),
	        		'placeholder' => false,
	        		'data' => false,
	        		'expanded' => true,
	        		'multiple' => false,
	        		'required' => false
	        ))
	        ->add('banner_overlay', CheckboxType::class, array(
	        		'label' => 'Dark Overlay',
	        		'required' => false
	        ))
	        ->add('filename', FileType::class, array(
	        		'data_class' => null,
	        		'label' => 'Banner*',
	        		'required' => false
	        ))
	        ->add('link_text', TextType::class, array(
	        		'label' => 'Link Button Text',
	        		'required' => false
	        ))
	        ->add('link_url', TextType::class, array(
	        		'label' => 'Link Button URL',
	        		'required' => false
	        ))
	    ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CmsBundle\Entity\ArchPageHomeSlide',
        ));
    }
}