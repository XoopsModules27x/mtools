<?php declare(strict_types=1);

namespace Tests\Unit\XoopsModules\Mtools\Common;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use XoopsModules\Mtools\Common\Db;

/**
 * Tests for the {@see Db} helpers extracted from SysUtility.
 *
 * `blockAddCatSelect()` is pure (no database) and runs in unit-only mode. The handle
 * methods take an explicit \XoopsMySQLDatabase, so they self-skip via RequiresXoops
 * until a runtime/stub provides that class.
 */
#[CoversClass(Db::class)]
final class DbTest extends TestCase
{
    use \RequiresXoops;

    public function testBlockAddCatSelectBuildsInList(): void
    {
        self::assertSame('(1,2,3)', Db::blockAddCatSelect([1, 2, 3]));
    }

    public function testBlockAddCatSelectSingleValue(): void
    {
        self::assertSame('(7)', Db::blockAddCatSelect([7]));
    }

    public function testBlockAddCatSelectEmptyOrNonArrayYieldsEmptyString(): void
    {
        self::assertSame('', Db::blockAddCatSelect([]));
        self::assertSame('', Db::blockAddCatSelect('not-an-array'));
    }

    public function testFieldExistsRejectsUnsafeIdentifiersWithoutQuerying(): void
    {
        // Needs the \XoopsMySQLDatabase type to mock; self-skips in unit-only mode.
        $this->requiresXoops();

        $db = $this->createMock(\XoopsMySQLDatabase::class);
        // An unsafe field name must be rejected by the allowlist before any query runs.
        $db->expects(self::never())->method('query');

        self::assertFalse(Db::fieldExists($db, 'bad field;', 'table'));
    }
}
