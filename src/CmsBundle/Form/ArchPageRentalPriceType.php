<?php
namespace CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ArchPageRentalPriceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('name', TextType::class, array(
	        		'required' => false
	        ))
	        ->add('items', TextType::class, array(
	        		'required' => false
	        ))
	        ->add('variants', CollectionType::class, array(
	        		'entry_type'    => ArchPageRentalPriceVariantType::class,
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
            'data_class' => 'CmsBundle\Entity\ArchPageRentalPrice',
        ));
    }
}