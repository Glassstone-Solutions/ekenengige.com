<?php get_header(); ?>
<div id="content">
    <div class="<?php echo wyde_get_layout_class(); ?>">    
        <?php wyde_page_background(); ?>
        <div class="container post-content">
            <div class="col-12">
                <div class="page-detail clear"> 
                    <div class="page-404-title">
                        <h1><?php echo __('404', 'flora');?></h1>
                        <h6><?php echo __('Page Not Found', 'flora'); ?></h6>
                    </div>
                <h4 class="page-404-text"><?php echo __( 'It looks like nothing was found at this location. Maybe try a search?', 'flora' ); ?></h4>
			    <?php get_search_form(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>