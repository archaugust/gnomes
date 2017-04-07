<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReferralType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => 'Name*'))
            ->add('dob', TextType::class, array(
            		'label' => 'Date of Birth*',
            ))
            ->add('nhi', TextType::class, array(
            		'label' => 'NHI',
            		'required' => false,
            		'empty_data' => ''
            ))
            ->add('parent_name', TextType::class, array(
            		'label' => 'Name',
            		'required' => false,
            		'empty_data' => ''
            ))
            ->add('phone', TextType::class, array('label' => 'Phone*'))
            ->add('phone_other', TextType::class, array(
            		'label' => 'Other Phone',
            		'required' => false,
            		'empty_data' => ''
            ))
            ->add('email', TextType::class, array(
            		'label' => 'Email',
            		'required' => false,
            		'empty_data' => ''
            ))
            ->add('referrer_name', TextType::class, array(
            		'label' => 'Name*',
            ))
            ->add('referrer_clinic', TextType::class, array(
            		'label' => 'Practice/Clinic*',
            ))
            ->add('referrer_email', EmailType::class, array(
            		'label' => 'Email*',
            ))
            ->add('reason', TextareaType::class, array(
            		'label' => 'Reason for Referral*',
            		'attr' => array('style' => 'height:170px')
            ))
            ->add(
            		$builder->create('if_acc', FormType::class, array('by_reference' => true))
			            ->add('claim_number', TextType::class, array(
			            		'label' => 'Claim Number',
			            		'required' => false,
			            		'empty_data' => ''
			            ))
			            ->add('date_of_accident', TextType::class, array(
			            		'label' => 'Date of Accident',
			            		'required' => false,
			            		'empty_data' => ''
			            ))
            			->add('teeth_registered', TextType::class, array(
            					'label' => 'Teeth Registered',
            					'required' => false,
            					'empty_data' => ''
            			))
				)
			->add('attachments', CollectionType::class, array(
					'entry_type'    => FileType::class,
					'entry_options' => array(
							'required' => false,
							'data_class' => null,
							'label' => false,
							'empty_data' => ''
					),
					'by_reference' => false,
					'allow_add'     => true,
					'allow_delete' => true,
					'delete_empty' => true,
					'label' => false,
					'required' => false,
					'empty_data' => array()
			))
			->add('send', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Referral',
        	'allow_extra_fields' => true
        ));
    }
}