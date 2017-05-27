<?php
namespace CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class ArchPageContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('intro', CKEditorType::class, array(
            		'label' => 'Intro',
            		'config' => array(
            				'height' => '200px',
            		),
            		'required' => false
            ))
            ->add('address', CKEditorType::class, array(
            		'config' => array(
            				'height' => '200px',
            		),
            		'required' => false
            ))
            ->add('phone', TextType::class, array('required' => false))
            ->add('email', TextType::class, array('required' => false))
            ->add('hours', CKEditorType::class, array(
            		'label' => 'Shop Hours',
            		'config' => array(
            				'height' => '200px',
            		),
            		'required' => false
            ))
            ->add('find', CKEditorType::class, array(
            		'label' => 'Find Us',
            		'config' => array(
            				'height' => '200px',
            		),
            		'required' => false
            ))
            ->add('lat', TextType::class, array('required' => false, 'label' => 'Latitude'))
            ->add('lng', TextType::class, array('required' => false, 'label' => 'Longitude'))
            ->add('zoom', TextType::class, array('required' => false, 'label' => 'Zoom Level'))
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
            'data_class' => 'CmsBundle\Entity\ArchPageContact',
        ));
    }
}