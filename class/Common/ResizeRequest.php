<?php declare(strict_types=1);

namespace XoopsModules\Mtools\Common;

/**
 * Immutable input for an image resize. Pure data — no request reads, no I/O.
 *
 * @api Stable Common-tier API (Lab\* is experimental, module-local code is private).
 * @since 1.2.0
 */
final class ResizeRequest
{
    /** Scale to fit INSIDE maxWidth x maxHeight, preserving aspect (no crop). */
    public const FIT_INSIDE = 'inside';

    /** Scale to COVER maxWidth x maxHeight, then centre-crop to exactly those dims. */
    public const FIT_COVER = 'cover';

    /** Stretch to exactly maxWidth x maxHeight, ignoring aspect ratio. */
    public const FIT_STRETCH = 'stretch';

    /**
     * @param string $sourcePath  path of the image to read
     * @param string $targetPath  path to write the resized image to
     * @param int    $maxWidth    target width bound (px)
     * @param int    $maxHeight   target height bound (px)
     * @param string $fit         one of FIT_INSIDE | FIT_COVER | FIT_STRETCH
     * @param int    $quality     output quality 0–100 (JPEG/WebP; mapped to PNG compression)
     * @param bool   $allowUpscale whether to enlarge images smaller than the target
     */
    public function __construct(
        public readonly string $sourcePath,
        public readonly string $targetPath,
        public readonly int $maxWidth,
        public readonly int $maxHeight,
        public readonly string $fit = self::FIT_INSIDE,
        public readonly int $quality = 85,
        public readonly bool $allowUpscale = false,
    ) {}
}
