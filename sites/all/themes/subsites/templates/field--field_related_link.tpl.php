<?php
if ($items) {?>
	<div class="sub-section">
		<p><b>Related Links:</b></p>
		<ul>
		<?php foreach ($items as $link) { ?>
	    	<li>
	    		<?php print render($link); ?>
	    	</li>
	    <?php }?>
	    </ul>
	</div>
<?php };
?>