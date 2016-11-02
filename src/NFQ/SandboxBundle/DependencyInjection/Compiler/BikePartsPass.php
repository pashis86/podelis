<?php

/**
 * Created by PhpStorm.
 * User: pc
 * Date: 16.11.2
 * Time: 17.42
 */
namespace NFQ\SandboxBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class BikePartsPass implements CompilerPassInterface
{
  public function process(ContainerBuilder $container)
  {
    if (!$container->has('app')) {
      return;
    }
    $definition = $container->findDefinition('app.bike');
    $taggedServices = $container->findTaggedServiceIds('app.bike.parts');
    foreach ($taggedServices as $id => $tags) {
      $definition->addMethodCall('addPart', array(new Reference($id)));
    }
  }
}