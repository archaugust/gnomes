<?php
namespace CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class ArchPageBootPriceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('name', CKEditorType::class, array(
	        		'label' => 'Label',
	        		'config' => array(
	        				'height' => '100px',
	        		),
	        		'required' => false
	        ))
	        ->add('price', TextType::class, array(
	        		'required' => false
	        ))
	        ->add('colour', TextType::class, array(
	        		'label' => 'Button Colour',
	        		'required' => false
	        ))
	    ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CmsBundle\Entity\ArchPageBootPrice',
        ));
    }
}