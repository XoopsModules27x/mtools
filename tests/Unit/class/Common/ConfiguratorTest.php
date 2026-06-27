<?php declare(strict_types=1);

namespace Tests\Unit\XoopsModules\Mtools\Common;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use XoopsModules\Mtools\Common\Configurator;

/**
 * Guards the Configurator base-directory contract.
 *
 * A missing/empty base directory must throw rather than silently falling back to
 * mtools' own directory (which used to load mtools' config for the consumer). These
 * checks run in unit-only mode because they fail before any config file is read.
 */
#[CoversClass(Configurator::class)]
final class ConfiguratorTest extends TestCase
{
    public function testNoArgumentConstructionThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Configurator();
    }

    public function testEmptyStringDirectoryThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Configurator('');
    }

    public function testWhitespaceOnlySlashDirectoryThrows(): void
    {
        // rtrim strips trailing slashes/backslashes; a slash-only path is effectively empty.
        $this->expectException(InvalidArgumentException::class);
        new Configurator('/');
    }

    public function testForModuleNamedConstructorExists(): void
    {
        self::assertTrue(method_exists(Configurator::class, 'forModule'));
    }
}
