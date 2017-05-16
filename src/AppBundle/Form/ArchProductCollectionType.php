<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArchProductCollectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => 'Name*'))
            ->add('handle', TextType::class, array('label' => 'Handle*'))
            ->add('banner_text', TextareaType::class, array('required' => false))
            ->add('banner_text_color', ChoiceType::class, array(
            		'label' => 'Banner text colour',
            		'choices' => array(
            				'Black' => true,
            				'White' => false,
            		),
            		'placeholder' => false,
            		'expanded' => true,
            		'multiple' => false,
            		'required' => false
            ))
            ->add('banner_overlay', CheckboxType::class, array(
            		'label' => 'Dark overlay',
            		'required' => false
            ))
            ->add('banner_sale', CheckboxType::class, array(
            		'label' => 'Sale Tag',
            		'required' => false
            ))
            ->add('is_active', ChoiceType::class, array(
            		'label' => 'Active',
            		'choices' => array(
            				'Yes' => true,
            				'No' => false
            		),
            ))
            ->add('description', CKEditorType::class, array(
            		'label' => 'Description', 
            		'required' => false,
            		'config' => array(
            				'height' => '200px',
            		),
            ))
            ->add('image', FileType::class, array(
            		'data_class' => null,
            		'required' => false,
            		'label' => 'Banner',
            ))
       		->add('meta_title', TextType::class, array(
       				'label' => 'Meta Title',
      				'required' => false
       		))
       		->add('meta_description', TextareaType::class, array(
       				'label' => 'Meta Description',
       				'required' => false
       		))
       		->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ArchProductCollection',
        ));
    }
}