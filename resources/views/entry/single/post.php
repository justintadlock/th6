<article <?php Hybrid\Attr\display( 'entry' ) ?>>

	<header class="entry__header">
		<?php Hybrid\Post\display_title() ?>

		<div class="entry__byline">
			<?php Hybrid\Post\display_author() ?>
			<?php Hybrid\Post\display_date( [ 'before' => TH6\sep() ] ) ?>
			<?php Hybrid\Post\display_comments_link( [ 'before' => TH6\sep() ] ) ?>
		</div>
	</header>

	<div class="entry__content">
		<?php the_content() ?>
		<?php Hybrid\View\display( 'nav/pagination', 'post' ) ?>
	</div>

	<footer class="entry__footer">
		<?php Hybrid\Post\display_terms( [ 'taxonomy' => 'category' ] ) ?>
		<?php Hybrid\Post\display_terms( [ 'taxonomy' => 'post_tag', 'before' => TH6\sep() ] ) ?>
	</footer>

</article>
