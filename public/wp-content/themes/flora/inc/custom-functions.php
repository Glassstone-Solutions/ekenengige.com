<?php

/* Apply attributes to HTML tags */
function wyde_get_attributes( $attributes = array() ) {

	$output = array();
	foreach ( $attributes as $name => $value ) {
        if( !empty($name) ){
			$output[] = !empty( $value ) ? sprintf( ' %s="%s"', esc_html( $name ), esc_attr( $value ) ) : esc_html( " {$name}" );
        }
	}
	return implode(' ', $output);

}

/* Get column CSS class */
function wyde_get_column_class($width, $width_sm, $offset = ''){
    
    $classes = array();

    $col_name = wyde_get_column_name($width);

    if( $offset ){
        $cols = preg_replace('/[^0-9]+/', '', $col_name);
        $classes[] = 'col-'. ( intval($cols) - intval($offset) );
        $classes[] = 'col-offset-'.intval($offset);
    }else{
        $classes[] = $col_name;
    }

    if( !empty($width_sm) ){
        $classes[] = 'col-resp';
        $classes[] = wyde_get_column_name($width_sm, 'sm-');
    } 

    return implode(' ', $classes);
}

/* Get column name */
function wyde_get_column_name($width, $prefix = ''){
    $col_name = '';
    if ( preg_match( '/^(\d{1,2})\/12$/', $width, $match ) ) {
		$col_name = 'col-'. $prefix . $match[1];
	} else {
		$col_name = 'col-'. $prefix;
		switch ( $width ) {
			case "1/6" :
				$col_name .= '2';
				break;
			case "1/4" :
				$col_name .= '3';
				break;
			case "1/3" :
				$col_name .= '4';
				break;
			case "1/2" :
				$col_name .= '6';
				break;
			case "2/3" :
				$col_name .= '8';
				break;
			case "3/4" :
				$col_name .= '9';
				break;
			case "5/6" :
				$col_name .= '10';
				break;
			case "1/1" :
				$col_name .= '12';
				break;
            case "1/5" :
				$col_name = 'five-cols';
				break;
			default :
				$col_name = 'col-'. $prefix . $width;
		}
	}

    return $col_name;
}

/** Extract inline css from custom css */
function wyde_get_inline_css($custom_css) {
	$css = preg_match( '/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', $custom_css ) ? preg_replace( '/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', '$2', $custom_css ) : '';
	return $css;
}

/* Primary Menu */
function wyde_primary_menu(){
    global $wyde_options;
    if( $wyde_options['onepage'] ){
        wp_nav_menu(array('theme_location' => 'primary', 'depth' => 0, 'container' => false, 'walker'=> new Wyde_Walker_OnePage_Nav, 'items_wrap' => '%3$s', 'fallback_cb'  => false) );                
    }else{
        wp_nav_menu(array('theme_location' => 'primary', 'depth' => 0, 'container' => false, 'walker'=> new Wyde_Walker_MegaMenu_Nav, 'items_wrap' => '%3$s', 'fallback_cb' => false));
    }
}

/* Vertical Menu */
function wyde_vertical_menu(){
    global $wyde_options;
    if( $wyde_options['onepage'] ){
        wp_nav_menu(array('theme_location' => 'primary', 'depth' => 0, 'container' => false, 'walker'=> new Wyde_Walker_OnePage_Vertical_Nav, 'items_wrap' => '%3$s', 'link_after' => '<span></span>', 'fallback_cb'  => false) );                
    }else{
        wp_nav_menu(array('theme_location' => 'primary', 'depth' => 0, 'container' => false, 'walker'=> new Wyde_Walker_Vertical_Nav, 'items_wrap' => '%3$s', 'link_after' => '<span></span>', 'fallback_cb' => false));
    }
}

/* Fullscreen Menu */
function wyde_fullscreen_menu(){
    add_filter( 'nav_menu_item_id', 'wyde_get_fullscreen_menu_id', 50, 4 );
    wyde_vertical_menu();
}

/* Get Fullscreen Menu Item ID */
function wyde_get_fullscreen_menu_id($id, $item, $args, $depth){
    $id = 'fullscreen-menu-item-'.$item->ID;
    return $id;
}

/* Default Menu */
function wyde_menu($location, $depth){
    wp_nav_menu(array('theme_location' => $location, 'depth' => $depth, 'container' => false, 'items_wrap' => '%3$s', 'fallback_cb' => false));
}

/* Footer Scripts */
function wyde_footer_content(){
    global $wyde_options;
    if( !empty($wyde_options['footer_script']) ){
        /**
        *Echo extra HTML/JavaScript/Stylesheet from theme options > advanced - body content
        */        
        echo balanceTags( $wyde_options['footer_script'], true );
    }
}

/* Predefined Colors */
function wyde_predifined_colors(){

    return array(
        '1' => '#10a5a0',
        '2' => '#80d5d8',
        '3' => '#594b4a',
        '4' => '#d5a63a',
        '5' => '#badbbe',
        '6' => '#ffd300',
        '7' => '#f08c74',
        '8' => '#0c709c',
        '9' => '#a62e2b',
    );

}

/* Get current color scheme */
function wyde_get_color_scheme(){
        global $wyde_options, $wyde_color_scheme;

        if(!$wyde_options['custom_color']){
            $colors = wyde_predifined_colors();
            $selected_color = $wyde_options['predefined_color'];
            if( isset($colors[$selected_color]) && !empty($colors[$selected_color]) ) $wyde_color_scheme = $colors[$selected_color];
            else $wyde_color_scheme = $colors[1];
        }else{
            $wyde_color_scheme = $wyde_options['color_scheme'];
        }

        return $wyde_color_scheme;
}

function wyde_get_color(){
    global $wyde_color_scheme;
    if( !$wyde_color_scheme ){
        $wyde_color_scheme = wyde_get_color_scheme();
    }
    return $wyde_color_scheme;
}

/* Get slider element */
function wyde_get_slider($id, $type){
    switch($type){
        case 'revslider':
        wyde_revslider($id);
        break;
        case 'layerslider':
        wyde_layerslider($id);
        break;
    }
}

/* Layer Slider */
function wyde_layerslider($id){
    echo do_shortcode('[layerslider id="' . $id . '"]');
}

/* Revolution Slider */
function wyde_revslider($id){
    echo do_shortcode('[rev_slider '.$id.']');
}

/* Page Title Area */
function wyde_page_title(){
    get_template_part('page', 'title'); 
}

/* Page Background */
function wyde_page_background(){
    get_template_part('page', 'background'); 
}

/* Sidebar */
function wyde_sidebar( $name = 'blog' ){
    global $wyde_sidebar_position, $wyde_page_id;
    
    $sidebar = get_post_meta( $wyde_page_id, '_w_sidebar_name', true );

    if( empty($sidebar) ){
        $sidebar = $name;
    } 

    $classes = array();
    $classes[] = 'sidebar';
    $classes[] = 'col-3';
    ?>
    <aside class="<?php echo implode(' ', $classes); ?>">
        <?php
        dynamic_sidebar($sidebar);
        ?>
    </aside>
    <?php
}

/* Get page layout classes */
function wyde_get_layout_class(){
    global $wyde_page_layout, $wyde_sidebar_position, $wyde_page_sidebar, $wyde_page_header, $wyde_header_overlay, $wyde_title_area;

    $wyde_page_sidebar = '';

    if( $wyde_page_layout == 'wide' ){
        $wyde_page_sidebar = 'full-width'; 
    }else{
        switch ($wyde_sidebar_position) {
            case '1':
                $wyde_page_sidebar = 'no-sidebar';
                break;
            case '2':
                $wyde_page_sidebar = 'left-sidebar';
                break;
            case '3':
                $wyde_page_sidebar = 'right-sidebar';
                break;
        }
    }

    $classes = array();
    $classes[] = 'main-content';
    $classes[] = $wyde_page_sidebar;

    if( !$wyde_title_area && (!$wyde_page_header || !$wyde_header_overlay) ) $classes[] = 'header-space';

    return implode(' ', $classes);
}

/* Get main content classes */
function wyde_get_main_class(){
    global $wyde_sidebar_position;

    $classes = array();
    $classes[] = 'main';
    if( $wyde_sidebar_position == '1' ){
        $classes[] = 'col-12';
    }else{
        $classes[] = 'col-9';
    }

    return implode(' ', $classes);    
}

/* Get embeded media preview */
function wyde_get_media_preview( $media_url ){

    if( strpos($media_url, 'vimeo.com') === false && strpos($media_url, 'youtube.com') === false ){

        $embed_code = '';
        $embed_code = wp_oembed_get($media_url, array(
                'width'     => '1170',
                'height'    => '658'
        ));

        if( !empty($embed_code) && preg_match('/src="([^"]+)"/', $embed_code, $match) ){
            return add_query_arg( array(
                'iframe' => 'true',
                'width' => '1170',
                'height' => '658'
            ), $match[1]); 
        }
    }

    return $media_url;

}

/* 
* Blog
* -----------------------------------------------*/
function wyde_set_post_views($post_id) {
    $count_key = '_w_post_views';
    $count = get_post_meta($post_id, $count_key, true);
    if($count == ''){
        $count = 0;
        delete_post_meta($post_id, $count_key);
        add_post_meta($post_id, $count_key, '0');
    }else{
        $count++;
        update_post_meta($post_id, $count_key, $count);
    }
}

function wyde_get_type_icon($post_id=''){
    if($post_id =='') $post_id = get_the_ID();
    $thumbnail = '';
    switch(get_post_type($post_id)){
        case 'page':
            $thumbnail = '<i class="flora-icon-comment-empty"></i>';
            break;
        case 'portfolio':
            $thumbnail = '<i class="flora-icon-bookmark"></i>';
            break;
        case 'product':
            $thumbnail = '<i class="flora-icon-baskett"></i>';
            break;
        default:
            $thumbnail = '<i class="flora-icon-comment-empty"></i>';
            break;
    }
    return $thumbnail;
}

function wyde_get_post_thumbnail($post_id='', $size='', $link=''){
    if($post_id == '') $post_id = get_the_ID();
    if($size == '') $size = 'thumbnail';

    $image =  wp_get_attachment_image_src(get_post_thumbnail_id($post_id), $size);
    if( $image ){
        $thumbnail = sprintf('<img src="%s" alt="%s" class="post-thumb" />', esc_url($image[0]), get_the_title() );
    }else{
        $format = get_post_format($post_id);
        $post_formats = get_theme_support( 'post-formats' );
        if( is_array($post_formats) && !in_array($format, $post_formats[0] ) ){
            $format = 'standard';
        } 
        $thumbnail = '<span class="post-thumb post-icon-'.$format.'"></span>';
    }

    if($link == ''){
        return $thumbnail;
    }else{
        return sprintf('<a href="%s" title="">%s</a>', esc_url( $link ), $thumbnail);
    }
}

function wyde_post_title( $link = '' ){

    switch ( get_post_format() ) {
        case 'link':
            if( is_single() ){
                the_title( '<h2 class="post-title">', '</h2>');
            }else{

                if(!$link){
                    $link = get_permalink();
                    the_title( '<h3 class="post-title"><a href="' . esc_url( $link ) . '">', '</a></h3>');
                }else{
                    the_title( '<h3 class="post-title"><a href="' . esc_url( $link ).'" target="_blank">', '</a></h3>');
                }
            }
            break;
        case 'quote':

            $quote = get_post_meta(get_the_ID(), '_w_post_quote', true );

            if( empty($quote) ) $quote = get_the_title();

            $author = get_post_meta(get_the_ID(), '_w_post_quote_author', true );

            if( !empty( $author ) ) $author = '<span class="quote-author">' . esc_html( $author ) . '</span>';

            if(is_single()){
                echo '<h2 class="post-title">'. esc_html( $quote ) . $author .'</h2>';
            }else{
                echo '<h3 class="post-title"><a href="' . esc_url( get_permalink() ) .'">'. esc_html( $quote ). '</a>'. $author .'</h3>';
            }

            break;
        default:

            if(is_single()){
                the_title( '<h2 class="post-title">', '</h2>');
            }else{
                the_title( '<h3 class="post-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h3>');
            }

            break;
    }
    
}

function wyde_get_single_category(){

    $categories = get_the_category(); 
    $category_names = array();
    if($categories){
	    foreach($categories as $category) {
            $category_names[] = esc_html($category->name);
	    }

        if($categories[0]){
            return '<a href="'. esc_url( get_category_link($categories[0]->term_id ) ) .'" title="'. esc_attr( implode(', ', $category_names) ) .'">'. esc_html( $categories[0]->name ) .'</a>';
        }
    }
    return '';
}

/** Custom Excerpt **/
function wyde_get_excerpt( $strip_html = true, $length = '' ) {
    
    global $wyde_options, $post;

	$content = '';

    $excerpt_length = intval( isset( $wyde_options['blog_excerpt_length'] ) ? $wyde_options['blog_excerpt_length'] : 55 );

    if( $length ){
        $excerpt_length = intval($length);
    }else{
        $excerpt_length = apply_filters('excerpt_length', $excerpt_length);
    }

    $readmore = ' [&hellip;]';
    
    $custom_excerpt = false;
     
    $post = get_post(get_the_ID());

    if( $strip_html ){

        $raw_content = strip_tags( $post->post_content );

    }else{

        $raw_content = get_the_content( __('Continue reading', 'flora') . ' <i class="flora-icon-right-small"></i>' );

        $pos = strpos($post->post_content, '<!--more-->');  

        if( $post->post_excerpt || $pos !== false ) {
            if( !$pos ) {
                $raw_content = rtrim( get_the_excerpt(), '[&hellip;]' ) . $readmore;
            }
            $custom_excerpt = true;
        }

    }
           
    if( $custom_excerpt == false && $raw_content ) {
    	$pattern = get_shortcode_regex();
    	$content = preg_replace_callback("/$pattern/s", 'wyde_remove_shortcode', $raw_content);

        if( $strip_html ){
            $content = wp_strip_all_tags( do_shortcode($content) );
        }

        $words = explode(' ', $content, $excerpt_length + 1);

    	if( count($words) > $excerpt_length ) {
    		array_pop($words);
            $content = implode(' ', $words);
            $content .= $readmore;
    	} else {
    		$content = implode(' ', $words);
    	}

        if( !$strip_html ){
            $content = preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', $content); // strip shortcode and keep the content
            $content = apply_filters('the_content', $content);
            $content = str_replace(']]>', ']]&gt;', $content);
        }

    }else{

        if( has_excerpt() ) {
    	    $content = do_shortcode( get_the_excerpt() );
            if( $strip_html ){
                $content =  wp_strip_all_tags( $content );
            }
        }else{
            $pattern = get_shortcode_regex();
    	    $content = preg_replace_callback("/$pattern/s", 'wyde_remove_shortcode', $raw_content);
           
            if( $strip_html ){
                $content = wp_strip_all_tags( do_shortcode($content) );
            }else{
                $content = apply_filters('the_content', $content);
            }

            $content = str_replace(']]>', ']]&gt;', $content);            
        }       

    }

	return $content;
}

function wyde_remove_shortcode( $m ) {

	if ( $m[2] == 'vc_row' ) {
        return  preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', $m[5]);
	}

    return $m[1] . $m[6];
}

function wyde_get_blog_masonry_layout( $masonry_layout = array(), $layout = '' ){
     
     switch( $layout ){
        case 'w-masonry':
        $masonry_layout = array('w-item w-w2 w-h2', 'w-item', 'w-item w-h2', 'w-item w-h2', 'w-item', 'w-item w-w2', 'w-item w-item-h', 'w-item', 'w-item w-w2 w-h2', 'w-item w-h2', 'w-item w-w2', 'w-item w-h2', 'w-item', 'w-item w-item-h', 'w-item', 'w-item w-h2', 'w-item w-w2 w-h2', 'w-item w-h2', 'w-item w-w2', 'w-item', 'w-item w-item-h');
        break;
        default:
        $masonry_layout = array('w-item', 'w-item w-h2', 'w-item', 'w-item w-h2', 'w-item w-h2', 'w-item w-h2', 'w-item', 'w-item');
        break;
    }

    return $masonry_layout;
}

function wyde_post_content( $view ){
    $template = '';
    switch($view){
        case 'grid':
        case 'masonry':
            $template = 'grid';
            break;
        case 'w-masonry':
            $template = 'masonry';
            break;
    }
    get_template_part('templates/blog/content', $template);
}

function wyde_blog_meta_share_icons(){
    get_template_part('templates/blog/meta', 'shareicons');
}

function wyde_post_nav(){  
    get_template_part('templates/blog/nav');
}

function wyde_post_author(){
    get_template_part('templates/blog/author');
}

function wyde_related_posts(){
    get_template_part('templates/blog/related');
}

function wyde_search_meta(){
    get_template_part('templates/blog/search', 'meta');
}

function wyde_comment($comment, $args, $depth) { 
    $add_below = ''; 
?>
<li <?php comment_class();?>>
    <article id="comment-<?php comment_ID() ?>" class="clear">
        <div class="avatar">
			<?php echo get_avatar($comment, $args['avatar_size']); ?>
		</div>
		<div class="comment-box">
			<h4 class="name"><?php echo get_comment_author_link(); ?></h4>
            <div class="post-meta"><span class="comment-date"><?php printf('%1$s at %2$s', get_comment_date(),  get_comment_time()) ?></span><?php edit_comment_link(__('Edit', 'flora'),'  ','') ?><?php comment_reply_link(array_merge( $args, array('reply_text' => __('Reply', 'flora'), 'add_below' => 'comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?></div>
			<div class="post-content">
				<?php if ($comment->comment_approved == '0') : ?>
				<em><?php echo __('Your comment is awaiting moderation.', 'flora') ?></em>
				<br />
				<?php endif; ?>
				<?php comment_text(); ?>
			</div>
		</div>
    </article>
<?php
}

function wyde_pagination($type = '', $pages = '', $range = 2){
    
    global $wyde_options, $wp_query;

    if($type == ''){
        $type = $wyde_options['blog_pagination'];
    }

    if($type == '1'){
        wyde_numberic_pagination($pages, $range);
    }else if($type == '2'){
        wyde_infinitescroll($pages, $range);
    }else{

        if($pages == '')
        {
             $pages = $wp_query->max_num_pages;
             if(!$pages)
             {
                 $pages = 1;
             }
        }

        if($pages != 1)
        {
            echo '<div class="pagination clear">';
            echo '<span class="w-previous">';
            previous_posts_link( __('Newer Entries', 'flora') );
            echo '</span>';
            echo '<span class="w-next">';
            next_posts_link( __('Older Entries', 'flora'), $pages );
            echo '</span>';
            echo '</div>';
        }
    }
    
}

function wyde_numberic_pagination($pages = '', $range = 2)
{   
     global $paged, $wp_query;
     $show_items = ($range * 2)+1;  
    
     if( empty($paged) ) $paged = 1;

     if($pages == '')
     {
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }   
    
     if($pages != 1)
     {
         echo '<div class="pagination numberic clear">';
         echo '<ul>';
         if( $paged == 1 ){
            echo '<li class="w-first disabled"><span><i class="flora-icon-double-left"></i></span></li>';         
            echo '<li class="w-prev disabled"><span><i class="flora-icon-left"></i></span></li>';         
         }else{
            echo '<li class="w-first"><a href="'. get_pagenum_link(1).'"><i class="flora-icon-double-left"></i></a></li>';
            echo '<li class="w-prev"><a href="'. get_pagenum_link($paged - 1) .'"><i class="flora-icon-left"></i></a></li>';
         } 


         for ($i = 1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= ($paged + $range + 1) || $i <= ( $paged - $range - 1) ) || $pages <= $show_items ))
             {
                 echo ( $paged == $i )? '<li><span class="current">'.$i.'</span></li>' : '<li><a href="'. get_pagenum_link($i).'">'.$i.'</a></li>';
             }
         }

         if( $paged == $pages ){
            echo '<li class="w-next disabled"><span><i class="flora-icon-right"></i></span></li>';
            echo '<li class="w-last disabled"><span><i class="flora-icon-double-right"></i></span></li>';
         }else{
            echo '<li class="w-next"><a href="'. get_pagenum_link($paged + 1) .'"><i class="flora-icon-right"></i></a></li>';
            echo '<li class="w-last"><a href="'. get_pagenum_link($pages) .'"><i class="flora-icon-double-right"></i></a></li>';
         } 
         echo '</ul>';
         echo '</div>';
     }
}

function wyde_infinitescroll($pages = '', $range = 2)
{  
     global $paged;
     if(empty($paged)) $paged = 1;

     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }   

     if($pages != 1 && $paged != $pages)
     {
         echo '<div class="w-showmore clear">';
         echo '<a href="'. get_pagenum_link($paged + 1).'" class="w-next">Show More</a>';
         echo '</div>';
     }
}


/*
* Portfolio
* ---------------------------------------*/
function wyde_portfolio_content( $view ){

    $template = '';
    if( $view == 'w-masonry' ){
        $template = 'masonry-flora';
    }elseif($view == 'masonry'){
        $template = 'masonry';
    }

    get_template_part('templates/portfolio/content', $template);
}

function wyde_portfolio_sidebar(){
    wyde_portfolio_widget('meta');
    wyde_portfolio_widget('clients');
    wyde_portfolio_widget('fields');
    wyde_portfolio_widget('categories');
    wyde_portfolio_widget('skills');
}

function wyde_portfolio_widget( $name = 'meta' ){
    get_template_part('templates/portfolio/widget', $name);
}

function wyde_portfolio_related(){
    get_template_part('templates/portfolio/related');
}

function wyde_portfolio_nav(){
    get_template_part('templates/portfolio/nav');
}

function wyde_get_portfolio_thumbnail($post_id='', $size='', $link=''){
    $thumbnail = '';
    if($post_id =='') $post_id = get_the_ID();
    if($size =='') $size = 'thumbnail';

    $image =  wp_get_attachment_image_src(get_post_thumbnail_id($post_id), $size);
    if( $image ){
        $thumbnail = sprintf('<img src="%s" alt="%s" class="post-thumb" />', esc_url($image[0]), get_the_title() );
    }else{
        $thumbnail = '<span class="post-thumb"></span>';
    }

    if($link == ''){
        return $thumbnail;
    }else{
        return sprintf('<a href="%s" title="">%s</a>', esc_url( $link ), $thumbnail);
    }
}

function wyde_get_portfolio_masonry_layout( $masonry_layout = array(), $layout = '' ){
     
     switch( $layout ){
        case 'w-masonry':
        $masonry_layout = array('w-item w-h2', 'w-item', 'w-item w-w2 w-h2', 'w-item', 'w-item w-w2 w-h2', 'w-item', 'w-item w-h2', 'w-item', 'w-item w-h2', 'w-item w-w2 w-h2', 'w-item w-h2', 'w-item w-w2', 'w-item', 'w-item');
        break;
        default:
        $masonry_layout = array('w-item', 'w-item w-h2');
        break;
    }

    return $masonry_layout;
}

/* Loader */
function wyde_page_loader( $loader = '' ){
    global $wyde_options;
    $output = '';
    if( !empty($loader) && $loader !== 'none' ){
        ob_start();
        ?>
        <div id="loading-animation" class="loader-<?php echo esc_attr( $wyde_options['page_loader'] );?>">
        <?php get_template_part('templates/loaders/loader', $wyde_options['page_loader']); ?>
        </div>
        <?php
        $output = ob_get_clean();
    }
    echo apply_filters('flora_page_loader', $output);
}

function wyde_string_to_underscore_name($string)
{
    $string = preg_replace('/[\'"]/', '', $string);
    $string = preg_replace('/[^a-zA-Z0-9]+/', '_', $string);
    $string = trim($string, '_');
    $string = strtolower($string);
    
    return $string;
}

/** Social Icons **/
function wyde_social_icons( $tooltip_position = 'top' ){
    global $wyde_options;

    $social_icons = wyde_get_social_icons();

    echo '<ul class="social-icons">';     
    foreach($social_icons as $key => $value){
        if( !empty( $wyde_options['social_'. wyde_string_to_underscore_name($value)] ) ){
            echo sprintf('<li><a href="%s" target="_blank" title="%s" data-placement="%s"><i class="%s"></i></a></li>', esc_url( $wyde_options['social_'. wyde_string_to_underscore_name($value)] ), esc_attr( $value ), esc_attr( $tooltip_position ), esc_attr( $key ));    
        }
    }
    echo '</ul>';
}

/** Contact Info **/
function wyde_contact_info(){
    global $wyde_options;
    if( is_array($wyde_options['menu_contact_items']) ):
    ?>
    <ul class="contact-info">
        <?php foreach( (array)$wyde_options['menu_contact_items'] as $item):?>
        <li><?php echo wp_kses_post( $item ); ?></li>
        <?php endforeach;?>
    </ul>
    <?php 
    endif;
}

/*
* Social Icons on Team Member option
*/
function wyde_get_social_icons(){
    
    $icons = array(
            'flora-icon-behance'        => 'Behance',
            'flora-icon-deviantart'     => 'DeviantArt',
            'flora-icon-digg'           => 'Digg',
            'flora-icon-dribbble'       => 'Dribbble',
            'flora-icon-dropbox'        => 'Dropbox',
            'flora-icon-facebook'       => 'Facebook',
            'flora-icon-flickr'         => 'Flickr',
            'flora-icon-github'         => 'Github',
            'flora-icon-google-plus'    => 'Google+',
            'flora-icon-instagram'      => 'Instagram',
            'flora-icon-linkedin'       => 'LinkedIn',
            'flora-icon-pinterest'      => 'Pinterest',
            'flora-icon-reddit'         => 'Reddit',
            'flora-icon-rss'            => 'RSS',
            'flora-icon-skype'          => 'Skype',
            'flora-icon-soundcloud'     => 'Soundcloud',
            'flora-icon-tumblr'         => 'Tumblr',
            'flora-icon-twitter'        => 'Twitter',
            'flora-icon-vimeo'          => 'Vimeo',
            'flora-icon-vk'             => 'VK',
            'flora-icon-yahoo'          => 'Yahoo',
            'flora-icon-youtube'        => 'Youtube',
    );

    return apply_filters('wyde_social_media_icons', $icons);
}

/*
* Animation options for content elements
*/
function wyde_get_animations(){
   return array(
            '' 										=> 'No Animation',
            'bounceIn'   			  				=> 'Bounce In',
            'bounceInUp'   		  					=> 'Bounce In Up',
            'bounceInDown'   	  					=> 'Bounce In Down',
            'bounceInLeft'   	  					=> 'Bounce In Left',
            'bounceInRight'     					=> 'Bounce In Right',
            'fadeIn'   				  			  	=> 'Fade In',
            'fadeInUp' 				  			  	=> 'Fade In Up',
            'fadeInDown'   		  					=> 'Fade In Down',
            'fadeInLeft'   		  					=> 'Fade In Left',
            'fadeInRight'   	  					=> 'Fade In Right',
            'fadeInUpBig'   						=> 'Fade In Up Long',
            'fadeInDownBig'   						=> 'Fade In Down Long',
            'fadeInLeftBig'   						=> 'Fade In Left Long',
            'fadeInRightBig'  						=> 'Fade In Right Long',
            'lightSpeedIn'   						=> 'Light Speed In',
            'pulse'						    		=> 'Pulse',
            'rollIn'   				        		=> 'Roll In',
            'rotateIn'   			          	    => 'Rotate In',
            'slideInUp'   		        		    => 'Slide In Up',
            'slideInDown'   	        		    => 'Slide In Down',
            'slideInLeft'   	        		    => 'Slide In Left',
            'slideInRight'   	        		    => 'Slide In Right',
            'swing'						        	=> 'Swing',
            'zoomIn'					      		=> 'Zoom In',
            );

}