<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ArchProductDiscounterFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('field', ChoiceType::class, array(
	        		'label' => false,
	        		'choices' => array(
	        			'Product type' => 'product_type',
	        			'Brand name' => 'brand_name',
	        			'Supplier' => 'supplier_name',
	        			'Tagged with' => 'tags',
	        			'Pre-selling' => 'pre_sell',
	        			'Site visibility' => 'is_active',
	        			'Vend Status' => 'vend_active'
	        		),
	        		'placeholder' => 'Choose one'
	        ))
	        ->add('value', TextType::class, array(
	        		'label' => false,
	        		'attr' => array(
	        				'class' => 'hidden'
	        		)
	        ))
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ArchProductDiscounterFilter',
        ));
    }
}