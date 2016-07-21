<?php
    global $wyde_options;
?>
<div class="post-meta">
    <?php if( $wyde_options['search_show_date'] !== '0' ){?>
    <span class="post-datetime">
        <a href="<?php echo esc_url( get_day_link( get_the_date('Y'), get_the_date('m'), get_the_date('d') ) );?>">
        <?php echo get_the_date('M j, Y');?>
        </a>
    </span>
    <?php }?>
    <?php if( $wyde_options['search_show_author'] !== '0' ){?>
    <span class="post-author">
        <?php echo the_author_posts_link();?>
    </span>
    <?php }?>
</div>