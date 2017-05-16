<?php
namespace CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ArchBlogMainType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('header', TextType::class, array('label' => 'Header*'))
            ->add('intro', TextareaType::class, array(
            		'label' => 'Intro',
            		'required' => false,
            ))
            ->add('banner_text', TextareaType::class, array('label' => 'Banner Text', 'required' => false))
            ->add('banner_text_color', ChoiceType::class, array(
            		'label' => 'Banner Text Colour',
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
            		'label' => 'Dark Overlay',
            		'required' => false
            ))
            ->add('filename', FileType::class, array(
            		'data_class' => null,
            		'label' => 'Banner*',
            		'required' => false
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
            'data_class' => 'CmsBundle\Entity\ArchBlogMain',
        ));
    }
}