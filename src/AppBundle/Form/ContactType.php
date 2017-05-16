<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
            		'label' => 'Your Name *',
            		'attr' => array(
            				'class' => 'u-form-control'
            		)
            ))
            ->add('email', EmailType::class, array(
            		'label' => 'Email *',
            		'attr' => array(
            				'class' => 'u-form-control'
            		)
            ))
            ->add('subject', TextType::class, array(
            		'label' => 'Subject *',
            		'attr' => array(
            				'class' => 'u-form-control'
            		)
            ))
            ->add('message', TextareaType::class, array(
            		'label' => 'Message *',
            		'attr' => array(
            				'class' => 'u-form-control'
            		)
            ))
            ->add('save', SubmitType::class, array(
            		'label' => 'SEND MESSAGE',
            		'attr' => array(
            				'class' => 'c-btn c-btn--gray c-btn--wide',
            		)
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Contact',
        ));
    }
}