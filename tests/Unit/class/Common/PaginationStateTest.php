<?php declare(strict_types=1);

namespace Tests\Unit\XoopsModules\Mtools\Common;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use XoopsModules\Mtools\Common\PaginationState;

/**
 * Golden test for the pure {@see PaginationState} math (no XOOPS/request/HTML).
 */
#[CoversClass(PaginationState::class)]
final class PaginationStateTest extends TestCase
{
    public function testPageCountAndClamp(): void
    {
        $state = new PaginationState(total: 95, limit: 10, currentPage: 999);
        self::assertSame(10, $state->pageCount());
        self::assertSame(10, $state->currentPage(), 'current page is clamped to the last page');
        self::assertTrue($state->isLast());
        self::assertFalse($state->hasNext());
    }

    public function testOffsetAndRangeMidList(): void
    {
        $state = new PaginationState(total: 95, limit: 10, currentPage: 3);
        self::assertSame(20, $state->offset());
        self::assertSame(21, $state->rangeStart());
        self::assertSame(30, $state->rangeEnd());
        self::assertTrue($state->hasPrevious());
        self::assertTrue($state->hasNext());
    }

    public function testLastPageIsPartial(): void
    {
        $state = new PaginationState(total: 95, limit: 10, currentPage: 10);
        self::assertSame(91, $state->rangeStart());
        self::assertSame(95, $state->rangeEnd(), 'last page is capped at the total, not current*limit');
    }

    public function testEmptyResult(): void
    {
        $state = new PaginationState(total: 0, limit: 10, currentPage: 1);
        self::assertSame(1, $state->pageCount());
        self::assertSame(0, $state->rangeStart());
        self::assertSame(0, $state->rangeEnd());
        self::assertTrue($state->isFirst());
        self::assertTrue($state->isLast());
        self::assertFalse($state->hasNext());
    }

    public function testBandWindow(): void
    {
        $state = new PaginationState(total: 250, limit: 10, currentPage: 15, pageLimit: 10);
        self::assertSame(25, $state->pageCount());
        self::assertSame(2, $state->band());
        self::assertSame(11, $state->bandStart());
        self::assertSame(20, $state->bandEnd());
        self::assertSame(range(11, 20), $state->pages());
    }

    public function testLimitClauseIsSafe(): void
    {
        $state = new PaginationState(total: 95, limit: 10, currentPage: 3);
        self::assertSame(' LIMIT 20, 10', $state->limitClause());
    }

    public function testZeroLimitCoercedToDefault(): void
    {
        $state = new PaginationState(total: 40, limit: 0);
        self::assertSame(20, $state->limit());
        self::assertSame(2, $state->pageCount());
    }
}
