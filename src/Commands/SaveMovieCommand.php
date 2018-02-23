<?php
namespace StremioSaver\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use StremioSaver\Seekers\FileSeeker;

class SaveMovieCommand extends Command
{
    protected function configure()
    {
        $this
        // the name of the command (the part after "bin/console")
        ->setName('save:movie')

        // the short description shown while running "php bin/console list"
        ->setDescription('Saves current movie')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command allows you to save the current movie')

        //Arguments
        ->addArgument('movieName', InputArgument::REQUIRED, 'Name of the movie (folder) to save');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fileSeeker = new FileSeeker();

        $output->writeln(['','','<info>Looking for available movies...</info>']);
        $movies = $fileSeeker->getAvailableMovieFiles();

        if(!$movies) return $output->writeln('<error>Could not find any movie</error>'); 
        
    	$output->writeln('<info>Found the following files: </info>');
    	$output->writeln($movies);
    	$output->writeln(['', "<info>Copying files into <comment>{$input->getArgument('movieName')}</comment> movie folder...</info>", '']);

    	$fileSeeker->saveMovie($input->getArgument('movieName'));
    }
}