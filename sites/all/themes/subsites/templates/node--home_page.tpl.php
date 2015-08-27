<!-- Home Content -->
<?php print render($content['field_slide']); ?>
<?php print render($content['field_page_title']); ?>
<?php print render($content['field_body']); ?>
<!-- Home Callout -->
<div id="home-callout" class="sub-section gray">
	<?php print render($content['field_callout_image']); ?>
	<?php print render($content['field_callout_title']); ?>
	<?php print render($content['field_callout_text']); ?>
	<?php print render($content['field_callout_link']); ?>
</div>
<!-- Recent News -->
<?php print views_embed_view('news_articles', 'recent_news', $node->nid); ?>