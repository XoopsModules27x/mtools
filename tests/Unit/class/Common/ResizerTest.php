<?php declare(strict_types=1);

namespace Tests\Unit\XoopsModules\Mtools\Common;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use XoopsModules\Mtools\Common\ResizeRequest;
use XoopsModules\Mtools\Common\Resizer;

/**
 * Real-image tests for the pure {@see Resizer} (GD-backed; XOOPS-free).
 *
 * Note: these assert geometry/contract, not pixel fidelity — eyeball real photo crops on
 * the live site before relying on it for user uploads.
 */
#[CoversClass(Resizer::class)]
final class ResizerTest extends TestCase
{
    private string $dir;

    protected function setUp(): void
    {
        if (!\extension_loaded('gd')) {
            self::markTestSkipped('GD extension not available.');
        }
        $this->dir = \sys_get_temp_dir() . '/mtools_resizer_' . \bin2hex(\random_bytes(4));
        \mkdir($this->dir);
    }

    protected function tearDown(): void
    {
        if (isset($this->dir) && \is_dir($this->dir)) {
            foreach (\glob($this->dir . '/*') ?: [] as $f) {
                @\unlink($f);
            }
            @\rmdir($this->dir);
        }
    }

    private function makePng(int $w, int $h): string
    {
        $img = \imagecreatetruecolor($w, $h);
        \imagefill($img, 0, 0, \imagecolorallocate($img, 120, 60, 200));
        $path = $this->dir . "/src_{$w}x{$h}.png";
        \imagepng($img, $path);

        return $path;
    }

    public function testFitInsidePreservesAspect(): void
    {
        $out    = $this->dir . '/inside.png';
        $result = (new Resizer())->resize(new ResizeRequest($this->makePng(200, 100), $out, 50, 50));

        self::assertTrue($result->ok, (string) $result->error);
        self::assertSame(50, $result->width);
        self::assertSame(25, $result->height);
        self::assertSame([50, 25], \array_slice((array) \getimagesize($out), 0, 2));
    }

    public function testFitCoverCropsToExactDims(): void
    {
        $out    = $this->dir . '/cover.png';
        $result = (new Resizer())->resize(
            new ResizeRequest($this->makePng(200, 100), $out, 50, 50, ResizeRequest::FIT_COVER)
        );

        self::assertTrue($result->ok, (string) $result->error);
        self::assertSame(50, $result->width);
        self::assertSame(50, $result->height);
    }

    public function testFitStretchUsesExactDims(): void
    {
        $out    = $this->dir . '/stretch.png';
        $result = (new Resizer())->resize(
            new ResizeRequest($this->makePng(200, 100), $out, 40, 70, ResizeRequest::FIT_STRETCH)
        );

        self::assertSame(40, $result->width);
        self::assertSame(70, $result->height);
    }

    public function testDoesNotUpscaleByDefault(): void
    {
        $out    = $this->dir . '/noup.png';
        $result = (new Resizer())->resize(new ResizeRequest($this->makePng(30, 30), $out, 200, 200));

        self::assertSame(30, $result->width, 'small images are not enlarged by default');
        self::assertSame(30, $result->height);
    }

    public function testUpscaleWhenAllowed(): void
    {
        $out    = $this->dir . '/up.png';
        $result = (new Resizer())->resize(
            new ResizeRequest($this->makePng(30, 30), $out, 200, 200, ResizeRequest::FIT_INSIDE, 85, true)
        );

        self::assertSame(200, $result->width);
        self::assertSame(200, $result->height);
    }

    public function testMissingSourceReturnsFailure(): void
    {
        $result = (new Resizer())->resize(
            new ResizeRequest($this->dir . '/missing.png', $this->dir . '/x.png', 50, 50)
        );

        self::assertFalse($result->ok);
        self::assertNotNull($result->error);
    }
}
