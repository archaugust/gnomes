<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	
    	$builder
            ->add('first_name', TextType::class, array('label' => 'First Name*'))
            ->add('last_name', TextType::class, array('label' => 'Last Name*'))
            ->add('phone', TextType::class, array('required' => false))
            ->add('mobile', TextType::class, array('required' => false))
            ->add('fax', TextType::class, array('required' => false))
            ->add('email', TextType::class, array('label' => 'Email*'))
            ->add('website', TextType::class, array('required' => false))
            ->add('physical_address1', TextType::class, array('label' => 'Street', 'required' => false))
            ->add('physical_address2', TextType::class, array('label' => 'Street (cont.)', 'required' => false))
            ->add('physical_suburb', TextType::class, array('label' => 'Suburb', 'required' => false))
            ->add('physical_city', TextType::class, array('label' => 'City', 'required' => false))
            ->add('physical_postcode', TextType::class, array('label' => 'Postcode', 'required' => false))
            ->add('physical_state', TextType::class, array('label' => 'Region', 'required' => false))
       		->add('physical_country_id', EntityType::class,
       				array(
       					'label' => 'Country', 
   						'class' => 'AppBundle:ArchCountry',
       					'choice_label' => 'name',
   						'choice_value' => 'countryId',
   						'placeholder' => 'Select Country',
       					'required' => false
            		)
			)
			->add('postal_address1', TextType::class, array('label' => 'Street', 'required' => false))
			->add('postal_address2', TextType::class, array('label' => 'Street (cont.)', 'required' => false))
			->add('postal_suburb', TextType::class, array('label' => 'Suburb', 'required' => false))
			->add('postal_city', TextType::class, array('label' => 'City', 'required' => false))
			->add('postal_postcode', TextType::class, array('label' => 'Postcode', 'required' => false))
			->add('postal_state', TextType::class, array('label' => 'Region', 'required' => false))
			->add('postal_country_id', EntityType::class,
					array(
						'label' => 'Country',
						'class' => 'AppBundle:ArchCountry',
						'choice_label' => 'name',
						'choice_value' => 'countryId',
						'placeholder' => 'Select Country',
						'required' => false
					)
			)
			->add('date_of_birth', DateType::class, array(
					'required' => false,
					'widget' => 'single_text'
			))
			->add('sex', ChoiceType::class, array(
					'required' => false,
					'choices' => array(
							'Male' => 'M',
							'Female' => 'F',
					),
					'multiple' => false,
					'expanded' => false
			))
			->add('save', SubmitType::class)
        ;
    }
    		
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
        ));
    }
}