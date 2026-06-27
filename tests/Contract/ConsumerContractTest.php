<?php declare(strict_types=1);

namespace XoopsModules\Mtools\Tests\Contract;

use PHPUnit\Framework\TestCase;
use XoopsModules\Mtools\Bootstrap;
use XoopsModules\Mtools\Common\SysUtility;
use XoopsModules\Mtools\Tests\Fixtures\Consumer\Helper as ConsumerHelper;
use XoopsModules\Mtools\Tests\Fixtures\Consumer\Utility as ConsumerUtility;

/**
 * Verifies the public API contract mtools offers to a downstream consumer.
 *
 * The consumer is an in-repo fixture ({@see ConsumerUtility} / {@see ConsumerHelper})
 * that mirrors a real module's shape. The suite deliberately does NOT load the sibling
 * `quotes` module from disk, so these tests pass whether or not any other module is
 * checked out alongside mtools.
 */
final class ConsumerContractTest extends TestCase
{
    public function testConsumerSeesMtoolsPublicApi(): void
    {
        require_once dirname(__DIR__, 2) . '/bootstrap.php';

        self::assertTrue(class_exists(Bootstrap::class));
        self::assertTrue(class_exists(SysUtility::class));
    }

    public function testConsumerUtilityExtendsMtoolsSysUtility(): void
    {
        require_once dirname(__DIR__, 2) . '/bootstrap.php';

        self::assertTrue(is_subclass_of(ConsumerUtility::class, SysUtility::class));
    }

    public function testConsumerProvidesSiblingHelperShape(): void
    {
        // mtools' consumer-aware helpers resolve the consumer's own "<ns>\Helper"
        // via getInstance(); assert the fixture exposes exactly that shape.
        self::assertTrue(class_exists(ConsumerHelper::class));
        self::assertTrue(method_exists(ConsumerHelper::class, 'getInstance'));
        self::assertInstanceOf(ConsumerHelper::class, ConsumerHelper::getInstance());
    }

    public function testMtoolsRuntimeReportsClearFailureWithoutXoopsBootstrap(): void
    {
        require_once dirname(__DIR__, 2) . '/bootstrap.php';

        $status = Bootstrap::checkRuntime();

        self::assertFalse($status['ok']);
        self::assertNotSame('', Bootstrap::statusMessage($status));
    }
}
