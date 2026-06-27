<{*
  This-page QR code block.
  SHOWCASE: rendered through the xoops/smartyextensions NavigationExtension
  `render_qr_code` plugin (registered site-wide by system/preloads/smartyextensions.php),
  not hand-rolled <img> markup. Bootstrap 5.
*}>
<div class="text-center">
    <{render_qr_code text=$block.url size=$block.width}>
</div>
