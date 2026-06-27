<?php declare(strict_types=1);

namespace Tests\Unit\XoopsModules\Mtools\Module;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use XoopsModules\Mtools\Module\ConsumerRuntime;

/**
 * Tests for the shared consumer-side runtime guard.
 *
 * Without a booted XOOPS, the underlying dependency check reports the module as
 * unavailable, so `dependencyError()` returns a non-empty message and `isReady()`
 * is false — which is exactly the behaviour a consumer relies on to degrade safely.
 */
#[CoversClass(ConsumerRuntime::class)]
final class ConsumerRuntimeTest extends TestCase
{
    public function testDependencyErrorIsNonEmptyWithoutXoops(): void
    {
        self::assertNotSame('', ConsumerRuntime::dependencyError());
    }

    public function testIsReadyIsFalseWithoutXoops(): void
    {
        self::assertFalse(ConsumerRuntime::isReady());
    }

    public function testIsReadyAgreesWithDependencyError(): void
    {
        self::assertSame('' === ConsumerRuntime::dependencyError(), ConsumerRuntime::isReady());
    }
}
