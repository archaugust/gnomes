<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArchVendWebhookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, array(
            		'label' => 'Type*',
            		'choices' => array(
           				'Product Update' => 'product.update',
           				'Inventory Update' => 'inventory.update',
           				'Customer Update' => 'customer.update',
           				'Sale Update' => 'sale.update',
           				'Consignment Send' => 'consignment.send',
            			'Consignment Receive' => 'consignment.receive',
            		),
            		'placeholder' => 'Choose one'
            ))
            ->add('url', TextType::class, array(
            		'label' => 'URL*',
            		'data' => 'https://',
            ))
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ArchVendWebhook',
        ));
    }
}