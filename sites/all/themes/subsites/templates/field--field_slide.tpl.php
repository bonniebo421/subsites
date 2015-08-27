<?php

include_once __DIR__ . '/template-functions.php'; 

if ($items) { ?>
<section id="slider">
    <ul class="bxslider">
        <?php foreach ($items as $slide) { ?>
        <li>
            <?php if ($title = mos_field_collection_item_value('slide_title', $slide)) { ?>
            <div id="slide-description">
                <h2>
                    <?php if ($url = mos_field_collection_item_value('slide_link', $slide, 'url')) { ?><a href="<?php print $url; ?>"><?php } print $title; if ($url) { ?></a><?php } ?>
                </h2>
                <?php if ($subtitle = mos_field_collection_item_value('slide_subtitle', $slide)) { ?>
                    <h3>
                        <?php if ($url) { ?><a href="<?php print $url; ?>"><?php } print $subtitle; if ($url) { ?></a><?php } ?>
                    </h3>
                <?php } ?>
                <?php if ($url) { ?><div class="cta"><a class="solid-cta" href="<?php print $url; ?>"><?php print mos_field_collection_item_value('slide_link', $slide, 'title'); ?></a></div><?php } ?>
            </div>
            <?php } ?>
            <?php if ($credit = mos_field_collection_item_value('slide_credit', $slide)) { ?>
                <p class="slide-photo-credit">
                    &copy; <?php print mos_field_collection_item_value('slide_credit', $slide); ?>
                </p>
            <?php } ?>
            <!-- youtube goes here --><div class="player"></div>
            <img src="<?php print file_create_url(mos_field_collection_item_value('slide_image', $slide, 'uri')); ?>" alt="<?php print mos_field_collection_item_value('slide_image', $slide, 'alt'); ?>" />
        </li><?php } ?>
        <!-- HIDE CONTROLS IF ONLY ONE SLIDE -->
        <?php if (count($items) <= 1) { ?>
            <style>.bx-controls{display: none}</style>
        <?php } ?>
    </ul>
</section>
<?php } ?>