<?php declare(strict_types=1);

namespace XoopsModules\Mtools\Tests\Fixtures\Consumer;

/**
 * Sibling Helper for the fixture consumer.
 *
 * mtools' shared static helpers resolve the *consumer's* own Helper via late static
 * binding (SysUtility::consumerHelper(): strip the called class' final segment and
 * append "\Helper", then call getInstance()). This fixture provides exactly that
 * shape — a `<consumer-namespace>\Helper` exposing a no-arg `getInstance()` — so the
 * contract test can assert the consumer layout without booting XOOPS or the real
 * quotes module.
 */
final class Helper
{
    private static ?self $instance = null;

    public static function getInstance(): self
    {
        return self::$instance ??= new self();
    }
}
