<?php
namespace StremioSaver\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use StremioSaver\Seekers\FileSeeker;

class CleanCacheCommand extends Command
{
    protected function configure()
    {
        $this
        // the name of the command (the part after "bin/console")
        ->setName('clean-cache')

        // the short description shown while running "php bin/console list"
        ->setDescription('Cleans all subtitles and movies cached by stremio')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command allows you to clean all cached movies and subtitles');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fileSeeker = new FileSeeker();

        $output->writeln(['','','<info>Cleaning cache files...</info>']);
        $fileSeeker->cleanCachedFiles();
    }
}