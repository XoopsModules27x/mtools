<?php

namespace Tests\Unit;

use XoopsModules\Mtools\Lab\Repository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

/**
 * Class RepositoryTest.
 */
#[CoversClass(\XoopsModules\Mtools\Lab\Repository::class)]
#[Group('legacy')]
final class RepositoryTest extends TestCase
{
    private Repository $repository;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @todo Correctly instantiate tested object to use it. */
        $this->repository = new Repository();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->repository);
    }

    public function testLoad(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}
