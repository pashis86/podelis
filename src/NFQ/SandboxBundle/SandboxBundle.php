<?php

namespace NFQ\SandboxBundle;

use NFQ\SandboxBundle\DependencyInjection\Compiler\BikePartsPass;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SandboxBundle extends Bundle
{
  public function build(ContainerBuilder $container)
  {
    $container->addCompilerPass(new BikePartsPass());
  }
}