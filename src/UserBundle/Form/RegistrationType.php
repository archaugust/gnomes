<?php
namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('first_name')
	        ->add('last_name')
	        ->add('accepts_marketing', CheckboxType::class, array(
	        		'mapped' => false,
	        		'required' => false,
	        ))
	    ;
    }

    public function getParent()
    {
    	return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getName()
    {
        return 'app_user_registration';
    }
}