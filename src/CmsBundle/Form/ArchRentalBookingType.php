<?php
namespace CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ArchRentalBookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('first_name', TextType::class, array(
            		'label' => 'First Name *',
            		'attr' => array('class' => 'u-form-control'),
            ))
            ->add('last_name', TextType::class, array(
            		'label' => 'Last Name *',
            		'attr' => array('class' => 'u-form-control'),
            ))
            ->add('phone', TextType::class, array(
            		'label' => 'Phone *',
            		'attr' => array('class' => 'u-form-control'),
            ))
            ->add('email', EmailType::class, array(
            		'label' => 'Email *',
            		'attr' => array('class' => 'u-form-control'),
            ))
            ->add('phone', TextType::class, array(
            		'label' => 'Phone *',
            		'attr' => array('class' => 'u-form-control'),
            ))
            ->add('collect_date', DateType::class, array(
            		'label' => 'Collecting *',
            		'attr' => array('placeholder' => 'Enter a Date', 'class' => 'u-form-control'),
            		'widget' => 'single_text'
            ))
            ->add('collect_time', TextType::class, array(
            		'label' => 'At *',
            		'attr' => array(
            				'placeholder' => 'Enter a Time',
            				'class' => 'u-form-control'
            		),
            ))
            ->add('return_date', DateType::class, array(
            		'label' => 'Returning *',
            		'attr' => array('placeholder' => 'Enter a Date', 'class' => 'u-form-control'),
            		'widget' => 'single_text'
            ))
            ->add('guests', CollectionType::class, array(
            		'entry_type'    => ArchRentalBookingGuestType::class,
            		'by_reference' => false,
            		'allow_add'     => true,
            		'allow_delete' => true,
            		'label' => false,
            		'attr' => array('class' => 'u-form-control'),
            ))
       		->add('save', SubmitType::class, array(
       				'label' => 'Submit Booking Request',
       				'attr' => array(
       						'class' => 'c-btn c-btn--gray c-btn--wide'
       				)
       		))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CmsBundle\Entity\ArchRentalBooking',
        ));
    }
}