<?php
/**
 * Created by PhpStorm.
 * User: eimantas
 * Date: 16.11.2
 * Time: 13.04
 */

namespace Eimantas\SandboxBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AppDebugCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:debug');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $discovery = $this->getContainer()->get('app.discovery');

        $output->writeln($discovery->getEngine());
        $output->writeln($discovery->getFuelLoad());
        $output->writeln($discovery->getCrew());
        $output->writeln($discovery->getHull());
        $output->writeln('Complete.');
    }
}