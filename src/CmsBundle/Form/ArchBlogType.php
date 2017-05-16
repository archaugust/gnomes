<?php
namespace CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ArchBlogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => 'Name*'))
            ->add('handle', TextType::class, array(
            		'label' => false,
            		'required' => false,
            		'attr' => array(
            				'class' => 'hidden'
            		)
            ))
            ->add('teaser', TextareaType::class, array(
            		'label' => 'Teaser',
            		'required' => false,
            ))
            ->add('image_main', FileType::class, array(
            		'data_class' => null,
            		'required' => false,
            		'label' => 'Image',
            ))
            ->add('image_main_caption', TextType::class, array(
            		'label' => 'Image Caption',
            		'required' => false,
            ))
            ->add('display_date', DateType::class, array(
            		'label' => 'Displayed Date',
            		'required' => false,
            		'widget' => 'single_text'
            ))
            ->add('content_main', CKEditorType::class, array(
            		'label' => 'Content',
            		'required' => false,
            ))
            ->add('content_bottom_title', TextType::class, array(
            		'label' => 'Title',
            		'required' => false,
            ))
            ->add('image_middle', FileType::class, array(
            		'data_class' => null,
            		'required' => false,
            		'label' => 'Image',
            ))
            ->add('image_middle_caption', TextType::class, array(
            		'label' => 'Image Caption',
            		'required' => false,
            ))
            ->add('content_bottom', CKEditorType::class, array(
            		'label' => 'Content',
            		'required' => false,
            ))
            ->add('meta_title', TextType::class, array(
       				'label' => 'Meta Title',
      				'required' => false
       		))
       		->add('meta_description', TextareaType::class, array(
       				'label' => 'Meta Description',
       				'required' => false
       		))
       		->add('is_active', ChoiceType::class, array(
       				'label' => 'Active',
       				'choices' => array(
       						'Yes' => true,
       						'No' => false
       				),
       		))
       		->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CmsBundle\Entity\ArchBlog',
        ));
    }
}