<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FM\ElfinderBundle\Form\Type\ElFinderType;

class GeneralInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name_first', TextType::class, array(
                'required' => true
            ))
            ->add('name_last', TextType::class, array(
                'required' => true
            ))
            ->add('name_preferred', TextType::class, array(
                'required' => false
            ))
            ->add('dob', DateType::class)
            ->add('gender', RadioType::class, array(
                'data' => 'M'
            ))
            ->add('gender', RadioType::class, array(
                'data' => 'F'
            ))
            ->add('address', TextType::class, array(
                'required' => false
            ))
            ->add('mother', TextType::class, array(
                'required' => false
            ))
            ->add('father', TextType::class, array(
                'required' => false
            ))
            ->add('phone_home', TextType::class, array(
                'required' => false
            ))
            ->add('phone_work', TextType::class, array(
                'required' => false
            ))
            ->add('phone_cell', TextType::class, array(
                'required' => false
            ))
            ->add('phone_other', TextType::class, array(
                'required' => false
            ))
            ->add('email', TextType::class, array(
                'required' => false
            ))
            ->add('contact_preferred', TextType::class, array(
                'required' => false
            ))
            ->add('save', SubmitType::class, array('label' => 'Submit'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Content',
        ));
    }
}