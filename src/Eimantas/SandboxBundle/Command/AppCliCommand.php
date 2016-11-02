<?php
/**
 * Created by PhpStorm.
 * User: eimantas
 * Date: 16.11.2
 * Time: 20.37
 */

namespace Eimantas\SandboxBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AppCliCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:cli');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $discovery = $this->getContainer()->get('app.discovery');

        $output->write('Engine: ');
        $output->writeln($discovery->getEngine());
        $output->write('Crew members: ');
        $output->writeln($discovery->getCrew());
        $output->write('Fuel capacity: ');
        $output->writeln($discovery->getFuelLoad());
        $output->write('Hull integrity: ');
        $output->writeln($discovery->getHull());
    }
}