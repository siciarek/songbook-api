<?php

namespace Acme\Bundle\ApiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\LockHandler;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Routing\Route;

class GoCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('go')
            ->setDescription('Just run, do not ask.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $url = $this->getContainer()->get('router')->generate('login_check', [], Router::ABSOLUTE_URL);
        $data = [
            'username' => 'user',
            'password' => 'password',
        ];

        $ch = curl_init();
        curl_setopt($ch,  CURLOPT_HEADER, true);
        curl_setopt($ch,  CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $resp = curl_exec($ch);
        $info =  curl_getinfo($ch);
        curl_close($ch);

        $output->writeln(json_encode($info, 128));
        $output->writeln(json_encode(json_decode($resp), 128));
    }

}
