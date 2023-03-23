<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Infrastructure\Filesystem\Locator;

use function array_merge;
use function is_file;
use function ksort;
use function ltrim;
use function rtrim;

final class MailSourceLocator implements MailSourceLocatorInterface
{
    /** @var array<int, array<array{0: string, 1: string}>> */
    private array $directories = [];

    /** @var array<array{0: string, 1: string}>|null  */
    private ?array $sortedDirectories = null;

    public function registerDirectory(string $directory, string $extension, int $priority = 0): void
    {
        $this->directories[$priority][] = [
            rtrim($directory, DIRECTORY_SEPARATOR),
            ltrim($extension, '.'),
        ];
    }

    public function locale(string $code, string $locale, ?string $postfix = null): ?string
    {
        $code = ltrim($code, DIRECTORY_SEPARATOR);

        foreach ($this->getSortedDirectories() as [$directory, $extension]) {
            $paths = [
                $directory . DIRECTORY_SEPARATOR . $code . $postfix . '.' . $locale . '.' . $extension,
                $directory . DIRECTORY_SEPARATOR . $code . $postfix . '.' . $extension,
            ];

            foreach ($paths as $path) {
                if (is_file($path)) {
                    return $path;
                }
            }
        }

        return null;
    }

    /**
     * @return array<array{0: string, 1: string}>
     */
    private function getSortedDirectories(): array
    {
        if (null !== $this->sortedDirectories) {
            return $this->sortedDirectories;
        }

        $directoriesByPriority = $this->directories;
        $sortedDirectories = [];

        ksort($directoriesByPriority);

        foreach ($directoriesByPriority as $directories) {
            $sortedDirectories[] = $directories;
        }

        return $this->sortedDirectories = array_merge(...$sortedDirectories);
    }
}
