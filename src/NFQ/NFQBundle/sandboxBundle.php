<?php

namespace NFQ\NFQBundle;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class sandboxBundle extends Bundle
{ protected function configure()
{
    $this
        ->setName('app:cli')
    ;
}
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $crane = $this->getContainer()->get('app.crane');
        $output->write('Frame - ');
        $output->writeln($crane->getBody());
        $output->write("Wheels - ");
        $output->writeln($crane->getChassi());
        $output->write("Body - ");
        $output->writeln($crane->getUpperworks());
        $output->writeln('To get original results comment PRE_CREATE event in BikeFactory');
    }

}
