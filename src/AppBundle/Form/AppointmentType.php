<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name_first', TextType::class, array('label' => 'First Name*'))
            ->add('name_last', TextType::class, array('label' => 'Last Name*'))
            ->add('name_preferred', TextType::class, array(
            	'label' => 'Preferred First Name',
            	'required' => false,	
            	'empty_data' => ''
            ))
            ->add('new', ChoiceType::class, array(
            		'expanded' => false,
            		'multiple' => false,
            		'choices' => array(
            				'' => '',
            				'Yes' => 'Yes',
            				'No' => 'No',
            		),
            		'label' => 'First Visit to Bright Smiles?*',
            ))
            ->add('dob', TextType::class, array(
            	'label' => 'Date of Birth*',
            	'required' => false,
            	'empty_data' => ''
            ))
            ->add('gender', ChoiceType::class, array(
            		'label' => 'Gender*',
            		'expanded' => false,
            		'multiple' => false,
            		'choices' => array(
            				'' => '',
            				'M' => 'M',
            				'F' => 'F'
            		),
            		'required' => false,
            		'empty_data' => '',
            		'attr' => array('class' => 'returning')
            ))
            ->add('address', TextareaType::class, array(
            	'label' => 'Address',
            	'required' => false,
            	'empty_data' => '',
            	'attr' => array('style' => 'height: 170px')
            ))
            ->add('mother', TextType::class, array(
            	'label' => 'Mother*',
            	'empty_data' => '',
            	'attr' => array('class' => 'returning')
            ))
            ->add('father', TextType::class, array(
            		'label' => 'Father*',
            		'empty_data' => '',
            		'attr' => array('class' => 'returning')
            ))
            ->add('phone_home', TextType::class, array(
            		'label' => 'Phone (Home)*',
            		'empty_data' => '',
            		'attr' => array('class' => 'returning')
            ))
            ->add('phone_work', TextType::class, array(
            		'label' => 'Phone (Work)*',
            		'empty_data' => '',
            		'attr' => array('class' => 'returning')
            ))
            ->add('phone_cell', TextType::class, array(
            		'label' => 'Phone (Mobile)*',
            		'empty_data' => '',
            		'attr' => array('class' => 'returning')
            ))
            ->add('phone_other', TextType::class, array(
            		'label' => 'Phone (Other)',
            		'empty_data' => '',
            		'required' => false,
            ))
            ->add('email', EmailType::class, array(
            		'label' => 'Email*'
            ))
            ->add('contact', ChoiceType::class, array(
            		'expanded' => true,
            		'multiple' => true,
            		'choices' => array(
            				'Phone' => 'Phone',
            				'Text' => 'Text',
            				'Email' => 'Email'
            		),
            		'label' => 'Preferred Contact*',
            		'empty_data' => '',
            		'attr' => array('class' => 'returning')
            ))
            ->add('appointment', ChoiceType::class, array(
            		'expanded' => false,
            		'multiple' => false,
            		'choices' => array(
            				'' => '',
            				'Check Up' => 'Check Up',
            				'Consultation' => 'Consultation',
            				'Other' => 'Other'
            		),
            		'label' => 'Type of Appointment*',
            		'empty_data' => '',
            		'attr' => array('class' => 'returning')
            ))
            ->add('appointment_other', TextareaType::class, array(
            		'label' => 'Let us know what you need',
            		'attr' => array('style' => 'height: 170px'),
            		'required' => false,
            		'empty_data' => '',
            ))
            ->add('referral', ChoiceType::class, array(
            		'expanded' => false,
            		'multiple' => false,
            		'choices' => array(
            				'' => '',
            				'Referred' => 'Referred',
            				'Self-referred' => 'Self-referred',
            		),
            		'label' => 'Have you been referred or are you referring yourself?*',
		            'required' => false,
		            'empty_data' => '',
            ))
            ->add('referred_by', ChoiceType::class, array(
            		'expanded' => false,
            		'multiple' => false,
            		'choices' => array(
            				'' => '',
            				'The Community Dental Service' => 'The Community Dental Service',
            				'A different dentist' => 'A different dentist',
            				'An orthodontist' => 'An orthodontist',
            				'Other' => 'Other'
            		),
            		'label' => 'Who have you been referred by?*',
		            'required' => false,
		            'empty_data' => '',
            ))
            ->add('referral_clinic', TextType::class, array(
            		'label' => 'Clinic',
            		'empty_data' => '',
            		'required' => false,
            ))
            ->add('referral_number', TextType::class, array(
            		'label' => 'Referral Number',
            		'empty_data' => '',
            		'required' => false,
            ))
            ->add('reason_consultation', TextareaType::class, array(
            		'label' => 'What is the reason for the consultation?*',
            		'required' => false,
            		'empty_data' => ''
            ))
            ->add('dentist', ChoiceType::class, array(
            		'expanded' => false,
            		'multiple' => false,
            		'choices' => array(
            				'' => '',
            				'Dr Joanna Pedlow' => 'Dr Joanna Pedlow',
            				'Dr Ellen Fei' => 'Dr Ellen Fei',
            				'Dr Helen Campbell' => 'Dr Helen Campbell',
            				"I don't mind :)" => "I don't mind :)"
            		),
            		'label' => 'Who would you like to see?*',
            ))
            ->add('schedule_days', ChoiceType::class, array(
            		'expanded' => true,
            		'multiple' => true,
            		'choices' => array(
            				'Monday' => 'Monday',
            				'Tuesday' => 'Tuesday',
            				'Wednesday' => 'Wednesday',
            				'Thursday' => 'Thursday',
            				'Friday' => 'Friday'
            		),
            		'label' => 'What days suit you?*',
            		'attr' => array('class' => 'block')
            ))
            ->add('schedule_time', ChoiceType::class, array(
            		'expanded' => true,
            		'multiple' => true,
            		'choices' => array(
            				'8:30 - 10:00am' => '8:30 - 10:00am',
            				'10:00am - 12:30pm' => '10:00am - 12:30pm',
            				'1:30 - 3:00pm' => '1:30 - 3:00pm',
            				'3:00 - 5:00pm' => '3:00 - 5:00pm',
            		),
            		'label' => 'What times suit you?*',
            		'attr' => array('class' => 'block')
            ))
            ->add('comments', TextareaType::class, array(
            		'label' => "Anything else you'd like us to know?",
            		'required' => false,
            		'empty_data' => ''
            ))
            ->add('send', SubmitType::class, array('label' => 'Submit Booking Request'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Appointment',
        ));
    }
}