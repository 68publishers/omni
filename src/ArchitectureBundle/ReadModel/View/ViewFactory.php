<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\ReadModel\View;

final class ViewFactory implements ViewFactoryInterface
{
	private ViewClassnameTranslatorInterface $viewClassnameTranslator;

	private array $viewClassnameTransformers;

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\ReadModel\View\ViewClassnameTranslatorInterface $viewClassnameTranslator
	 * @param \SixtyEightPublishers\ArchitectureBundle\ReadModel\View\ViewDataTransformerInterface[]   $viewClassnameTransformers
	 */
	public function __construct(ViewClassnameTranslatorInterface $viewClassnameTranslator, array $viewClassnameTransformers)
	{
		$this->viewClassnameTranslator = $viewClassnameTranslator;
		$this->viewClassnameTransformers = (static fn (ViewDataTransformerInterface ...$viewClassnameTransformers): array => $viewClassnameTransformers)(...$viewClassnameTransformers);
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(string $viewClassname, ViewDataInterface $viewData): ViewInterface
	{
		$viewClassname = $this->viewClassnameTranslator->translate($viewClassname);

		foreach ($this->viewClassnameTransformers as $viewClassnameTransformer) {
			if ($viewClassnameTransformer->canTransform($viewClassname, $viewData)) {
				$viewData = $viewClassnameTransformer->transform($viewData, $this);
			}
		}

		return ([$viewClassname, 'fromData'])($viewData);
	}
}
