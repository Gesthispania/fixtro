<?php

declare(strict_types=1);

namespace KarlosAgudo\Fixtro\Tests\CodeQualityTool\Commands\Mock;

use KarlosAgudo\Fixtro\CodeQualityTool\Commands\GeneralCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CommandCorrect extends GeneralCommand
{
	protected $analyzers = [
		[
			'process' => 'KarlosAgudo\Fixtro\CodeQualityTool\Checker\NameSpaceFixer',
			'filter' => 'getPhpFiles',
		],
];

	/**
	 * Configure command.
	 */
	protected function configure()
	{
		$this->setName('correct-test');
	}

	/**
	 * @param InputInterface  $input
	 * @param OutputInterface $output
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 *
	 * @return int
	 *
	 * @throws \InvalidArgumentException
	 * @throws \Exception
	 */
	public function execute(InputInterface $input, OutputInterface $output): int
	{
		$files = [];

		return $this->executeCheckersAndShowResult($output, $files);
	}
}
