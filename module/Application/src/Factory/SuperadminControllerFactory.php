<?php
namespace Application\Factory;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class SuperadminControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* get helpermanager gan */
        $headScript = $container->get('ViewHelperManager')->get('headScript');

        return new \Application\Controller\SuperadminController($headScript);
    }
}