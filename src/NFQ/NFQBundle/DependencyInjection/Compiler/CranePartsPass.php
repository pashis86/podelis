<?php
/**
 * Created by PhpStorm.
 * User: sarunas
 * Date: 16.11.2
 * Time: 21.02
 */

namespace NFQ\NFQBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class CranePartsPass implements CompilerPassInterface
{  public function process(ContainerBuilder $container)
{
    if (!$container->has('app')) {
        return;
    }
    $definition = $container->findDefinition('app.crane');
    $taggedServices = $container->findTaggedServiceIds('app.crane.parts');
    foreach ($taggedServices as $id => $tags) {
        $definition->addMethodCall('addPart', array(new Reference($id)));
    }
}

}