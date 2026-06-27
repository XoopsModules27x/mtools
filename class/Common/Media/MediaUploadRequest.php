<?php declare(strict_types=1);

namespace XoopsModules\Mtools\Common\Media;

/**
 * Immutable description of one image upload to ingest.
 *
 * Storage-agnostic: the caller supplies the absolute target directories and the
 * limits. Persistence is the consuming module's job (see {@see MediaRepositoryInterface}).
 *
 * @api Stable Common-tier API.
 * @since 1.3.0
 */
final class MediaUploadRequest
{
    /**
     * @param array<string,mixed> $file            one $_FILES[...] entry
     * @param string              $targetDir       absolute dir for the original
     * @param string              $thumbnailDir    absolute dir for thumbnails ('' = none)
     * @param int[]               $thumbnailWidths widths to generate
     * @param string[]            $allowedMime     accepted MIME types
     */
    public function __construct(
        public readonly array $file,
        public readonly string $targetDir,
        public readonly string $thumbnailDir = '',
        public readonly array $thumbnailWidths = [150, 400],
        public readonly array $allowedMime = ['image/jpeg', 'image/pjpeg', 'image/png', 'image/gif', 'image/webp'],
        public readonly int $maxBytes = 5242880,
        public readonly int $maxWidth = 4000,
        public readonly int $maxHeight = 4000,
        public readonly string $namePrefix = 'img',
        public readonly int $quality = 85,
    ) {
    }
}
