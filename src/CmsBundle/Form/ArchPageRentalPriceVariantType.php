<?php
namespace CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ArchPageRentalPriceVariantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('name', TextType::class, array(
	        		'required' => false
	        ))
	        ->add('prices', TextType::class, array(
	        		'label' => 'Pricing',
	        		'attr' => array(
	        				'class' => 'tags'
	        		),
	        		'required' => false
	        ))
	    ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CmsBundle\Entity\ArchPageRentalPriceVariant',
        ));
    }
}