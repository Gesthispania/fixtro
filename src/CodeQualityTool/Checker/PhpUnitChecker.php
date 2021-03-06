<?php

declare(strict_types=1);

namespace KarlosAgudo\Fixtro\CodeQualityTool\Checker;

use Symfony\Component\Process\Process;

class PhpUnitChecker extends AbstractChecker implements CheckerInterface
{
	/** @var string */
	protected $title = 'Executing PhpUnit';

	/** @var array */
	protected $filterOutput = [
		' by Sebastian Bergmann and contributors.',
		'\.+',
		'S+',
		'\(100\%\)',
		'Time: (\d)+ ms, Memory: (\d)+\.(\d)+MB',
		'OK \((\d)+ tests\,',
		'OK ',
];

	/**
	 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
	 *
	 * @throws \Symfony\Component\Process\Exception\InvalidArgumentException
	 */
	public function process()
	{
		$buildFile = $this->findBuildPhpUnitFile();
		$process = new Process(
			[
				$this->fixtroVendorRootPath.'/bin/php_no_xdebug',
				$this->findBinary('phpunit'),
				'-c',
				$buildFile,
				//'--stop-on-error',
				//'--stop-on-failure',
			]
		);

		$process->setWorkingDirectory($this->fixtroVendorRootPath);
		$process->setTimeout(3600);
		$this->setProcessLine($process->getCommandLine());
		$process->run(function ($type, $buffer) {
			$this->outputChecker[] = $buffer;
		});

		$this->outputChecker[] = $process->getOutput();

		if (false !== stripos(implode('', $this->outputChecker), 'ERROR') ||
			false !== stripos(implode('', $this->outputChecker), 'failure')
		) {
			$this->errors = $this->outputChecker;
			$this->errors[] = 'EXECUTED:'.str_replace("'", '', $process->getCommandLine());
		}
	}

	/**
	 * @return string
	 */
	private function findBuildPhpUnitFile(): string
	{
		if (isset($this->parameters['confFile']) &&
			file_exists($this->parameters['confFile'])) {
			return $this->parameters['confFile'];
		}

		$possibleFiles = [
			'build/phpunit-test.xml',
			'build/phpunit.xml',
			'phpunit.xml',
			'phpunit.xml.dist',
			'../phpunit.xml',
			'../build/phpunit.xml',
			'../build/phpunit-test.xml',
		];

		// if not found use fixtro vendor one
		$defaultBuildFile = $this->fixtroVendorRootPath.'/build/phpunit.xml';

		foreach ($possibleFiles as $buildFile) {
			if (file_exists($this->projectPath.'/'.$buildFile)) {
				return $this->projectPath.'/'.$buildFile;
			}
		}

		return $defaultBuildFile;
	}
}
