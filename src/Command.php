<?php

namespace Meeva\CreateVendor;

use Composer\Command\BaseCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends BaseCommand
{
    protected function configure()
    {
        $this->setName('create-vendor')
            ->addArgument('name', null, InputArgument::REQUIRED, 'Name of the package');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cwd  = getcwd();
        $name = $input->getArgument('name');
        if (!preg_match('{^[a-z0-9_.-]+/[a-z0-9_.-]+$}', $name)) {
            throw new \InvalidArgumentException(
                'The package name ' . $name . ' is invalid, it should be lowercase and have a vendor name, a forward slash, and a package name, matching: [a-z0-9_.-]+/[a-z0-9_.-]+'
            );
        }

        $dirName = str_replace('/', DIRECTORY_SEPARATOR, $name);

        // change to global dir
        $config = Plugin::createConfig();
        $path   = $config->get('createVendorHome') . DIRECTORY_SEPARATOR . $dirName;
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        chdir($path);
        $this->getIO()->writeError('<info>Changed current directory to ' . $path . '</info>');

        $initArguments = array(
            'command' => 'init',
            '--name'  => $name,
        );
        $this->getApplication()->doRun(new ArrayInput($initArguments), $output);
        $this->resetComposer();

        chdir($cwd);
        $this->getIO()->writeError('<info>Changed current directory to ' . $cwd . '</info>');

        $version = 'dev-master';
        if (0 === strpos($version, 'dev-')) {
            $version .= '@dev'; // Ensure stability
        }

        $requireArguments = array(
            'command'  => 'require',
            'packages' => [$name . ':' . $version],
        );
        $this->getApplication()->doRun(new ArrayInput($requireArguments), $output);
    }
}
