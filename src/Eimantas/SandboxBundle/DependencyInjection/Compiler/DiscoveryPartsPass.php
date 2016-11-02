<?php
/**
 * Created by PhpStorm.
 * User: eimantas
 * Date: 16.11.2
 * Time: 13.39
 */

namespace Eimantas\SandboxBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DiscoveryPartsPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        if (!$container->has('app'))
        {
            return;
        }

        $definition = $container->findDefinition('app.discovery');

        $taggedServices = $container->findTaggedServiceIds('app.discovery.parts');

        foreach ($taggedServices as $id => $tags)
        {
            $definition->addMethodCall('addPart', [new Reference($id)]);
        }
    }
}