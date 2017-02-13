<?php

/** @var ClassLoader $loader */
$loader = require __DIR__.'/../vendor/autoload.php';

require_once __DIR__.'/AppKernel.php';

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Input\ArrayInput;
use Doctrine\Bundle\DoctrineBundle\Command\DropDatabaseDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\CreateDatabaseDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\Proxy\CreateSchemaDoctrineCommand;
use Doctrine\Bundle\FixturesBundle\Command\LoadDataFixturesDoctrineCommand;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

$kernel = new AppKernel('test', true); // create a "test" kernel
$kernel->boot();

$application = new Application($kernel);

// add the database:drop command to the application and run it
$command = new DropDatabaseDoctrineCommand();
$application->add($command);
$input = new ArrayInput(array(
    'command' => 'doctrine:database:drop',
    '--force' => true,
));
$command->run($input, new ConsoleOutput());

// add the database:create command to the application and run it
$command = new CreateDatabaseDoctrineCommand();
$application->add($command);
$input = new ArrayInput(array(
    'command' => 'doctrine:database:create',
));
$command->run($input, new ConsoleOutput());

// add the schema:create command to the application and run it
$command = new CreateSchemaDoctrineCommand();
$application->add($command);
$input = new ArrayInput(array(
    'command' => 'doctrine:schema:create',
));

$command->run($input, new ConsoleOutput());

// load fixtures
$command = new LoadDataFixturesDoctrineCommand();
$application->add($command);
$input = new ArrayInput(array(
    'command' => 'doctrine:fixtures:load',
    '--fixtures=' . dirname(__DIR__) . '/src/AppBundle/DataFixtures/ORM --env=test'
));
$input->setInteractive(false);
$command->run($input, new ConsoleOutput());

AnnotationRegistry::registerLoader([$loader, 'loadClass']);

return $loader;