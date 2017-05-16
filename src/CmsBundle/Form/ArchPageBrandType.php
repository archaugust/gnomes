<?php
namespace CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class ArchPageBrandType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('intro', CKEditorType::class, array(
            		'label' => 'Intro',
            		'required' => false,
            		'config' => array(
            				'height' => '200px',
            		),
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
            'data_class' => 'CmsBundle\Entity\ArchPageBrand',
        ));
    }
}