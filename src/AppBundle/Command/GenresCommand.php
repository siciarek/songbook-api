<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\LockHandler;
use Symfony\Component\DomCrawler\Crawler;

class GenresCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('gen')
            ->setDescription('Fetch all the music genres exposed on Wikipedia.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Create the lock:
        $lock = new LockHandler('go');
        if (!$lock->lock()) {
            return 0xFF;
        }

        $url = 'https://en.wikipedia.org/wiki/List_of_popular_music_genres';

        $content = file_get_contents($url);

        $crawler = new Crawler($content);

        $headers = $crawler->filter('h2 span.mw-headline, h3 span.mw-headline');
        $body = $crawler->filter('h2 ~ ul, h2 ~ div.div-col');

        $genres = [];

        $headers->each(function($e) use (&$genres) {
            $genres[] = $e->text();
        });
        array_pop($genres);
        array_pop($genres);
        array_pop($genres);
        array_pop($genres);

//        ldd($genres);

        $bodies = [];

        $body->each(function($e) use (&$bodies) {
            $bodies[] = $e->text();
        });

        $result = [];
        for($i = 0; $i < count($genres); $i++) {
            $list = trim($bodies[$i]);
            $list = explode("\n", $list);
            $list = array_map('trim', $list);
            $list = array_filter($list);
            $list = array_values($list);
            sort($list);

            $result[$genres[$i]] = $list;
        }



        echo json_encode($result, JSON_PRETTY_PRINT);

        // Release the lock:
        $lock->release();
    }

}
