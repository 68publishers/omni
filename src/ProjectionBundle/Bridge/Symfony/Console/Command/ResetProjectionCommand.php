<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\Bridge\Symfony\Console\Command;

use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use SixtyEightPublishers\ProjectionBundle\Projection\ProjectionClassnameResolver;
use SixtyEightPublishers\ProjectionBundle\ProjectionStore\ProjectionStoreInterface;

final class ResetProjectionCommand extends Command
{
	protected static $defaultName = 'omni:projection:reset';

	private ProjectionStoreInterface $projectionStore;

	private ProjectionClassnameResolver $projectionClassnameResolver;

	public function __construct(ProjectionStoreInterface $projectionStore, ProjectionClassnameResolver $projectionClassnameResolver)
	{
		parent::__construct();

		$this->projectionStore = $projectionStore;
		$this->projectionClassnameResolver = $projectionClassnameResolver;
	}

	protected function configure(): void
	{
		$this->setDescription('Resets data and positions for specific projection.')
			->addArgument('projection-name', InputArgument::REQUIRED, 'The name of a projection.');
	}

	/**
	 * @throws \SixtyEightPublishers\ProjectionBundle\ProjectionStore\ProjectionStoreException
	 */
	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$projectionName = $input->getArgument('projection-name');

		try {
			$projectionClassname = $this->projectionClassnameResolver->resolve($projectionName);
		} catch (InvalidArgumentException $e) {
			$output->writeln($e->getMessage());

			return Command::INVALID;
		}

		$helper = $this->getHelper('question');
		$question = new ConfirmationQuestion(sprintf('Do you really want to reset the "%s" projection? [y/n]: ', $projectionName), FALSE);

		if ($input->isInteractive() && !$helper->ask($input, $output, $question)) {
			return Command::SUCCESS;
		}

		$this->projectionStore->resetProjection($projectionClassname);
		$output->writeln('The projection has been successfully reset.');

		return Command::SUCCESS;
	}
}
