<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Infrastructure\Filesystem\Locator;

use function array_merge;
use function is_file;
use function ksort;
use function ltrim;
use function pathinfo;
use function rtrim;
use function strlen;
use function substr;

final class MailSourceLocator implements MailSourceLocatorInterface
{
    /** @var array<int, array<string>> */
    private array $directories = [];

    /** @var array<string>|null  */
    private ?array $sortedDirectories = null;

    public function registerDirectory(string $directory, int $priority = 0): void
    {
        $this->directories[$priority][] = rtrim($directory, DIRECTORY_SEPARATOR);
    }

    public function locale(string $code, string $locale, ?string $postfix = null): ?string
    {
        $code = ltrim($code, DIRECTORY_SEPARATOR);
        $pathInfo = pathinfo($code);
        $extension = '';

        if (isset($pathInfo['extension'])) {
            $code = substr($code, 0, (strlen($pathInfo['extension']) + 1) * -1);
            $extension = '.' . $pathInfo['extension'];
        }

        foreach ($this->getSortedDirectories() as $directory) {
            $paths = [
                $directory . DIRECTORY_SEPARATOR . $code . $postfix . '.' . $locale . $extension,
                $directory . DIRECTORY_SEPARATOR . $code . $postfix . $extension,
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
     * @return array<string>
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
