<?php

namespace Knp\RevealBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class SlideReindexCommand extends ContainerAwareCommand
{
    protected $changedFiles = array();

    public function configure()
    {
        $this
            ->setName('knp:slides:reindex')
            ->setDescription('This comment takes all files in a directory and prefix them with an integer.')
            ->addArgument('directory', InputArgument::REQUIRED, 'The slide directory you want to reindex.')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Just output the changes, no processing')
            ->addOption('git', null, InputOption::VALUE_NONE, 'Use git mv instead of renaming files in filesystem')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $directory = $input->getArgument('directory');
        if (!is_dir($directory)) {
            throw new \Exception(sprintf('`%s` is not a valid directory !'));
        }
        $directory = rtrim($directory, '/');

        $finder = new Finder();
        $finder->sortByName();
        $files = $finder->files()->in($directory);
        $fileCount = $finder->count();
        $padding = strlen((string)$fileCount);

        $index = 0;
        foreach ($files as $file) {
            $filename = $file->getFilename();
            $newFilename = $this->getIndexedFilename($filename, $index, $padding);

            if ($filename !== $newFilename) {
                $this->changedFiles[] = array(
                    'old' => $filename,
                    'new' => $newFilename
                );
            }

            $index++;
        }

        $this->fixRenamingConflicts($output);

        foreach ($this->changedFiles as $changedFile) {
            $this->renameFile(
                $input,
                $directory,
                $changedFile['old'],
                $changedFile['new']
            );

            $output->writeln(sprintf(
                '<comment>%s</comment> %s <comment>%s</comment>', 
                $changedFile['old'], 
                $input->getOption('dry-run')? 'would be renamed' : 'have been renamed',
                $changedFile['new']
            ));
        }
    }

    protected function getIndexedFilename($filename, $index, $padding)
    {
        $paddedIndex = str_pad($index, $padding, '0', STR_PAD_LEFT);

        if (preg_match('/^([0-9]+)(.*)/', $filename, $matches)) {
            return $paddedIndex.$matches[2];
        }

        return sprintf('%s_%s', $paddedIndex, $filename);
    }

    protected function renameFile(InputInterface $input, $directory, $filename, $newFilename)
    {
        if ($input->getOption('dry-run')) {
            return;
        }

        if ($input->getOption('git')) {
            exec(sprintf(
                'git mv -f %s %s',
                sprintf('%s/%s', $directory, $filename),
                sprintf('%s/%s', $directory, $newFilename)
            ));

            return;
        }

        $this
            ->getContainer()
            ->get('filesystem')
            ->rename(
                sprintf('%s/%s', $directory, $filename),
                sprintf('%s/%s', $directory, $newFilename),
                true
            )
        ;
    }

    protected function fixRenamingConflicts(OutputInterface $output)
    {
        foreach ($this->changedFiles as $index => $changedFile) {
            if ($this->isConflicting($index, $changedFile['old'], $changedFile['new'])) {
                $output->writeln(sprintf(
                    '<error>%s</error> is conflicting with <error>%s</error>', 
                    $changedFile['old'], 
                    $changedFile['new']
                ));

                die(1);
            }
        }
    }

    protected function isConflicting($oldIndex, $oldFilename, $newFilename)
    {
        foreach ($this->changedFiles as $index => $changedFile) {
            if ($newFilename == $changedFile['old'] && $oldIndex < $index) {
                return true;
            }
        }

        return false;
    }
}