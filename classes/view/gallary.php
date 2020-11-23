<?php get_header(); ?>
<section>
	<div class="content">
		<?php foreach ($gallary['image'] as $img) :?>
			<div class="grid-3">
				<div class="img-container">
					<label> <?php echo $img['link']  ?></label>
					<img class="minimized" src="<?php echo $img['link'] ?>">
				</div>
			</div>
		<?php endforeach; ?>
	</div>

</section>
<?php get_footer(); ?>
