<?php
namespace ShopBundle\Extensions;

use Symfony\Bridge\Doctrine\RegistryInterface;

class TwigExtensions extends \Twig_Extension
{    
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('productVariants', array($this, 'productVariants')),
        );
    }
    
    protected $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function productVariants($id){
        $em = $this->doctrine->getManager();
        $repository = $em->getRepository('AppBundle:ArchProduct');

        return $repository->findBy(array('variant_parent_id' => $id));
    }
    
    public function getName()
    {
        return 'Twig ShopBundle Extensions';
    }
}