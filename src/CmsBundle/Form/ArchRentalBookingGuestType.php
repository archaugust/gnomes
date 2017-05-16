<?php
namespace CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ArchRentalBookingGuestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$height = $weight = $shoe_size = $age = array();
    	for ($i=148; $i<=204; $i=$i+2)
    		$height[$i] = $i;
    	for ($i=44; $i<=104; $i=$i+2)
    		$weight[$i] = $i;
    	for ($i=0; $i<16; $i=$i+0.5) 
    		$shoe_size["$i"] = "$i";
   		for ($i=0; $i<100; $i++)
   			$age[$i] = $i;
   		$sizes = array(
   				"X-Small" => "X-Small",
   				"Small" => "Small",
   				"Medium" => "Medium",
   				"Large" => "Large",
   				"X-Large" => "X-Large",
   				"XX-Large" => "XX-Large",
   				"XXX-Large" => "XXX-Large",
   				"Age 2" => "Age 2",
   				"Age 4" => "Age 4",
   				"Age 6" => "Age 6",
   				"Age 8" => "Age 8",
   				"Age 10" => "Age 10",
   				"Age 12" => "Age 12",
   				"Age 14" => "Age 14"
   		);
   		
        $builder
	        ->add('first_name', TextType::class, array(
	        		'label' => 'First Name *',
	        		'attr' => array('class' => 'u-form-control'),
	        ))
	        ->add('last_name', TextType::class, array(
	        		'label' => 'Last Name *',
	        		'attr' => array('class' => 'u-form-control'),
	        ))
	        ->add('height', ChoiceType::class, array(
	        		'label' => 'Height (cm) *',
	        		'choices' => $height,
	        		'attr' => array('class' => 'u-form-control'),
	        ))
	        ->add('weight', ChoiceType::class, array(
	        		'label' => 'Weight (kg) *',
	        		'choices' => $weight,
	        		'attr' => array('class' => 'u-form-control'),
	        ))
	        ->add('shoe_size', ChoiceType::class, array(
	        		'label' => 'Shoe Size (UK) *',
	        		'choices' => $shoe_size,
	        		'attr' => array('class' => 'u-form-control'),
	        ))
	        ->add('shoe_size', ChoiceType::class, array(
	        		'label' => 'Shoe Size (UK) *',
	        		'choices' => $shoe_size,
	        		'attr' => array('class' => 'u-form-control'),
	        ))
	        ->add('age', ChoiceType::class, array(
	        		'label' => 'Age',
	        		'required' => false,
	        		'choices' => $age,
	        		'attr' => array('class' => 'u-form-control'),
	        ))
	        ->add('ability_level', ChoiceType::class, array(
	        		'label' => 'Ability Level',
	        		'choices' => array(
	        				'Beginner' => 'Beginner',
	        				'Intermediate' => 'Intermediate',
	        				'Advanced' => 'Advanced'
	        		),
	        		'attr' => array('class' => 'u-form-control'),
	        ))
	        ->add('gear', ChoiceType::class, array(
	        		'label' => 'Gear *',
	        		'choices' => array(
	        				"Skis, Boots & Poles" => "Skis, Boots & Poles",
	        				"Skis & Poles" => "Skis & Poles",
	        				"Skis Only" => "Skis Only",
	        				"Ski Boots Only" => "Ski Boots Only",
	        				"Snowboard & Boots" => "Snowboard & Boots",
	        				"Snowboard Only" => "Snowboard Only",
	        				"Snowboard Boots Only" => "Snowboard Boots Only",
	        				"Clothing Only" => "Clothing Only",
	        				"Other" => "Other"
	        		),
	        		'attr' => array('class' => 'u-form-control'),
	        ))
	        ->add('gear_type', ChoiceType::class, array(
	        		'label' => 'Gear Type *',
	        		'choices' => array(
	        				'Standard' => 'Standard',
	        				'Performance' => 'Performance',
	        				'Demo' => 'Demo',
	        				'Touring' => 'Touring'
	        		),
	        		'attr' => array('class' => 'u-form-control'),
	        ))
	        ->add('snowboard_stance', ChoiceType::class, array(
	        		'label' => 'Snowboard Stance',
	        		'required' => false,
	        		'choices' => array(
	        				"Left Foot Forward/Regular" => "Left Foot Forward/Regular",
	        				"Right Foot Forward/Goofy" => "Right Foot Forward/Goofy"
	        		),
	        		'attr' => array('class' => 'u-form-control'),
	        ))
	        ->add('jacket', ChoiceType::class, array(
	        		'label' => 'Jacket',
	        		'required' => false,
	        		'choices' => $sizes,
	        		'attr' => array('class' => 'u-form-control'),
	        ))
	        ->add('pants', ChoiceType::class, array(
	        		'label' => 'Pants',
	        		'required' => false,
	        		'choices' => $sizes,
	        		'attr' => array('class' => 'u-form-control'),
	        ))
	        ->add('goggles', ChoiceType::class, array(
	        		'label' => 'Goggles',
	        		'required' => false,
	        		'choices' => array(
	        				'Toddler' => 'Toddler',
	        				'Youth' => 'Youth',
	        				'Adult' => 'Adult'
	        		),
	        		'attr' => array('class' => 'u-form-control'),
	        ))
	        ->add('gloves', ChoiceType::class, array(
	        		'label' => 'Gloves',
	        		'required' => false,
	        		'choices' => $sizes,
	        		'attr' => array('class' => 'u-form-control'),
	        ))
	        ->add('helmet', ChoiceType::class, array(
	        		'label' => 'Helmet',
	        		'required' => false,
	        		'choices' => array(
		        			'Small' => 'Small',
		        			'Medium' => 'Medium',
		        			'Large' => 'Large',
		        			'Child X-Small/Small' => 'Child X-Small/Small',
		        			'Child Medium/Large' => 'Child Medium/Large'
	        		),
	        		'attr' => array('class' => 'u-form-control'),
	        ))
	        ->add('chains', TextType::class, array(
	        		'required' => false,
	        		'label' => 'Chains',
	        		'attr' => array('class' => 'u-form-control', 'placeholder' => 'Enter Tyre Size'),
	        ))
	        ->add('instructions', TextareaType::class, array(
	        		'label' => 'Special Instructions',
	        		'required' => false,
	        		'attr' => array('class' => 'u-form-control', 'placeholder' => 'Anything else we should know?'),
	        ))
	        
	        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CmsBundle\Entity\ArchRentalBookingGuest',
        ));
    }
}