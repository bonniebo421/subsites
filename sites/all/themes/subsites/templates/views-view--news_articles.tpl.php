<?php 
$rows = array();

foreach ($variables['view']->result as $result) {
    $node = node_load($result->nid);

    $rows[] = array(
    	'image'   => $node->field_thumbnail_image['und'][0]['uri'],
    	'alt'   => $node->field_thumbnail_image['und'][0]['alt'],
        'title'   => $node->field_page_title['und'][0]['safe_value'],
        'date' => $node->field_article_publish_date['und'][0]['value'],
        'body' => $node->field_body['und'][0]['safe_value'],
        'url' => $node->url_alias = url("node/$node->nid")
    );
}

?> 

<div class="sub-section">
	<h1>NCTL NEWS</h1>
	<ul id="news">
	    <?php foreach ($rows as $row) { ?><li>
	    	<?php if ($row['image']) { ?>
	    		<img src="<?php print file_create_url($row['image']); ?>" alt="<?php print $row['title']; ?>" />
	    	<?php } else { ?>
	    		<img src="sites/nctl/files/default_images/default-thumb_0.jpg" alt="<?php print $row['title']; ?>" />
	    	<?php } ?>
	        <h3><a href="<?php print $row['url']; ?>"><?php print $row['title']; ?></a></h3>
	        <p><span class="date"><?php print date('F d, Y', strtotime(render($row['date']))).':';?></span> <span class="snippet"><?php print substr(strip_tags($row['body']), 0, 300).'... '; ?><a href="<?php print $row['url']; ?>">Read full article</a></span></p>
	    </li><?php } ?>
	</ul>
</div>