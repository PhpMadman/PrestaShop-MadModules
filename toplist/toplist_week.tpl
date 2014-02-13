
<!-- Block toplist -->
<div id="toplist_block" class="block_toplist">
<!--   <h4>Bästsäljande mobiltelefoner i sverige vecka {$toplist_week}</h4> -->
  <div class="block_content_toplist">
	{if $logged}
		<a href="{$base_dir}modules/toplist/toplist_page.php"><img src="{$base_dir}modules/toplist/podium.jpg">
		</a>
	{else}
		<img src="{$base_dir}modules/toplist/podium_blur.jpg">
	{/if}
  </div>
</div>
<!-- /Block toplist -->