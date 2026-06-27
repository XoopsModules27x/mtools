<{*
  App download / setup QR codes.
  SHOWCASE: QR images come from the xoops/smartyextensions NavigationExtension
  `render_qr_code` plugin (BS5), replacing the former static image + inline
  Google-Charts <img>. No tadtools, no bundled QR asset.
*}>
<div class="row">
    <div <{if $block.direction=='h'}>class="col-sm-6"<{/if}> >
        <a href="<{$block.url1|escape}>" target="_blank" rel="noopener noreferrer"><{render_qr_code text=$block.url1 size=$block.width}></a>
        <div><{$smarty.const._MB_MTOOLS_APP_DOWNLOAD}></div>
    </div>
    <div <{if $block.direction=='h'}>class="col-sm-6"<{/if}> >
        <{render_qr_code text=$block.url2 size=$block.width}>
        <div><{$smarty.const._MB_MTOOLS_APP_SETUP}></div>
    </div>
</div>
