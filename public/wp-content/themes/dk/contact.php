<?php
/**
 * Template Name: Contact
 * The main template file for display contact page.
 *
 * @package WordPress
*/


/**
*	if not submit form
**/
if(!isset($_POST['your_name']))
{

/**
*	Get Current page object
**/
$current_page = get_page($post->ID);
$current_page_id = '';

if(isset($current_page->ID))
{
    $current_page_id = $current_page->ID;
}

get_header(); 

?>
		<?php
		$bg_style = get_post_meta($current_page_id, 'page_bg_style', true);
		
		if($bg_style == 'Static Image')
		{
			if(has_post_thumbnail($current_page_id, 'full'))
			{
			    $image_id = get_post_thumbnail_id($current_page_id); 
			    $image_thumb = wp_get_attachment_image_src($image_id, 'full', true);
			    $pp_page_bg = $image_thumb[0];
			}
			else
			{
				$pp_page_bg = get_stylesheet_directory_uri().'/example/bg.jpg';
			}
		?>
		<script type="text/javascript"> 
			jQuery.backstretch( "<?php echo $pp_page_bg; ?>", {speed: 'slow'} );
		</script>
		
		<?php
		} // end if static image
		else
		{
			$page_bg_gallery_id = get_post_meta($current_page_id, 'page_bg_gallery_id', true);
			$args = array( 
				'post_type' => 'attachment', 
				'numberposts' => -1, 
				'post_status' => null, 
				'post_parent' => $page_bg_gallery_id,
				'order' => 'ASC',
				'orderby' => 'menu_order',
			); 
			$bg_photo_arr = get_posts( $args );
		?>
		
		<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/supersized.css" type="text/css" media="screen" />

		<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/supersized.3.1.3.js"></script>
		<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/supersized.shutter.js"></script>
		
		<script type="text/javascript">  
			
			jQuery(function($){
				$.supersized({
				
					//Functionality
					slideshow               :   1,		//Slideshow on/off
					autoplay				:	1,		//Slideshow starts playing automatically
					start_slide             :   1,		//Start slide (0 is random)
					random					: 	0,		//Randomize slide order (Ignores start slide)
					slide_interval          :   10000,	//Length between transitions
					<?php						
						$pp_homepage_slideshow_trans = get_option('pp_homepage_slideshow_trans');
						
						if(empty($pp_homepage_slideshow_trans))
						{
							$pp_homepage_slideshow_trans = 1;
						}
					?>
					transition              :   <?php echo $pp_homepage_slideshow_trans; ?>, 		//0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
					transition_speed		:	500,	//Speed of transition
					new_window				:	1,		//Image links open in new window/tab
					pause_hover             :   0,		//Pause slideshow on hover
					keyboard_nav            :   1,		//Keyboard navigation on/off
					performance				:	1,		//0-Normal, 1-Hybrid speed/quality, 2-Optimizes image quality, 3-Optimizes transition speed // (Only works for Firefox/IE, not Webkit)
					image_protect			:	0,		//Disables image dragging and right click with Javascript

					//Size & Position
					min_width		        :   0,		//Min width allowed (in pixels)
					min_height		        :   0,		//Min height allowed (in pixels)
					vertical_center         :   1,		//Vertically center background
					horizontal_center       :   1,		//Horizontally center background
					<?php						
						$pp_enable_fit_image = get_option('pp_enable_fit_image');
						
						if(empty($pp_enable_fit_image))
						{
							$pp_enable_fit_image = 1;
						}
						else
						{
							$pp_enable_fit_image = 0;
						}
					?>
					fit_portrait         	:   <?php echo $pp_enable_fit_image; ?>,		//Portrait images will not exceed browser height
					fit_landscape			:   <?php echo $pp_enable_fit_image; ?>,		//Landscape images will not exceed browser width
					
					//Components
					navigation              :   1,		//Slideshow controls on/off
					thumbnail_navigation    :   0,		//Thumbnail navigation
					slide_counter           :   0,		//Display slide numbers
					slide_captions          :   0,		//Slide caption (Pull from "title" in slides array)
					progress_bar			:	1,
					slides 					:  	[		//Slideshow Images
														  
	

		<?php
			foreach($bg_photo_arr as $key => $photo)
			{
			    $small_image_url = get_stylesheet_directory_uri().'/images/000_70.png';
			    $hyperlink_url = get_permalink($photo->ID);
			    
			    if(!empty($photo->guid))
			    {
			    	$image_url[0] = $photo->guid;
			    
			    	$small_image_url = get_stylesheet_directory_uri().'/timthumb.php?src='.$image_url[0].'&amp;h=80&amp;w=130&amp;zc=1';
			    }

		?>

        	<?php $homeslides .= '{image : \''.$image_url[0].'\', thumb: \''.$small_image_url.'\', title: "<div id=\"gallery_title\">'.htmlentities($photo->post_title).'</div><div id=\"gallery_desc\">'.htmlentities($photo->post_content).'</div>"},'; ?>
        	
        <?php
        	}
        ?>

						<?php $homeslides = substr($homeslides,0,-1);
						echo $homeslides; ?>						]
												
				}); 
		    });
		    
		</script>
		
		<!--Time Bar-->
		<div id="progress-back" class="load-item">
			<div id="progress-bar"></div>
		</div>
		
		<div id="thumb-tray" class="load-item">
			<div id="thumb-back"></div>
			<div id="thumb-forward"></div>
		</div>
		
		<!--Control Bar-->
		<div id="controls-wrapper" class="load-item">
			<div id="controls">
				
				<!--Arrow Navigation--> 
				<a id="prevslide" class="load-item"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon_prev.png" alt=""/></a>
				
				<a id="play-button"><img id="pauseplay" src="<?php echo get_stylesheet_directory_uri(); ?>/images/pause.png" alt=""></a>
				 
				<a id="nextslide" class="load-item"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon_next.png" alt=""/></a>		
				<!--Thumb Tray button-->
				<a id="tray-button"><img id="tray-arrow" src="<?php echo get_stylesheet_directory_uri(); ?>/images/button-tray-up.png" alt=""/></a>	
			</div>
			
		</div>
		
		<input type="hidden" id="pp_image_path" name="pp_image_path" value="<?php echo get_stylesheet_directory_uri(); ?>/images/"/>
		
		<?php
		}
		?>

		<!-- Begin content -->
		<div id="page_content_wrapper">
		
			<div class="inner">
			
			<div id="page_caption" class="sidebar_content full_width" style="padding-bottom:0">
				<div style="float:left">
					<h1 class="cufon"><?php the_title(); ?></h1>
				</div>
				<div class="page_control">
					<a id="page_minimize" href="#">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon_minus.png" alt=""/>
					</a>
					<a id="page_maximize" href="#">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon_plus.png" alt=""/>
					</a>
				</div>
			</div>
			
			<div class="sidebar_content" style="width:43%;margin-top:20px">
				
				<!-- Begin main content -->
				<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>		

							<?php the_content(); ?>

						<?php endwhile; ?>
						
						<?php
							$pp_contact_form = unserialize(get_option('pp_contact_form_sort_data'));
						?>
						<form id="contact_form" name="contact_form" method="post" action="<?php echo curPageURL(); ?>">
							<?php 
								if(is_array($pp_contact_form) && !empty($pp_contact_form))
								{
									foreach($pp_contact_form as $form_input)
									{
										switch($form_input)
										{
											case 1:
							?>
											 <input id="your_name" name="your_name" type="text" style="width:94%" placeholder="Name*"/><br/><br/>				
							<?php
											break;
											
											case 2:
							?>
											<input id="email" name="email" type="text" style="width:94%" placeholder="Email*"/><br/><br/>				
							<?php
											break;
											
											case 3:
							?>
											 <textarea id="message" name="message" rows="7" cols="10" style="width:94%" placeholder="Message*"></textarea><br/><br/>				
							<?php
											break;
											
											case 4:
							?>
											<input id="address" name="address" type="text" style="width:94%" placeholder="Address"/><br/><br/>		
							<?php
											break;
											
											case 5:
							?>
											<input id="phone" name="phone" type="text" style="width:94%" placeholder="Phone"/><br/><br/>				
							<?php
											break;
											
											case 6:
							?>
											<input id="mobile" name="mobile" type="text" style="width:94%" placeholder="Mobile"/><br/><br/>				
							<?php
											break;
											
											case 7:
							?>
											<input id="company" name="company" type="text" style="width:94%" placeholder="Address"/><br/><br/>				
							<?php
											break;
											
											case 8:
							?>
											<input id="country" name="country" type="text" style="width:94%" placeholder="Country"/><br/><br/>				
							<?php
											break;
										}
									}
								}
							?>
						    <p style="margin-top:20px"><br/>
								<input type="submit" value="Send Message"/>
							</p>
						</form>
						<div id="reponse_msg"></div>
				<!-- End main content -->
				</div>
				
				<div class="sidebar_wrapper" style="width:40%;margin-top:-5px">
						<div class="sidebar" style="width:100%">
							
							<div class="content">
							
								<ul class="sidebar_widget">
									<?php 
										$page_sidebar = get_post_meta($current_page_id, 'page_sidebar', true);

										if(empty($page_sidebar))
										{
											$page_sidebar = 'Page Sidebar';
										}
										dynamic_sidebar($page_sidebar); ?>
								</ul>
								
							</div>
						
						</div>
						<br class="clear"/>
			
			</div>
			</div>
			
			<br class="clear"/>
			<?php get_footer(); ?>
		</div>
		<!-- End content -->
				
		<br class="clear"/>
		</div>
				
				
<?php
}

//if submit form
else
{
	check_ajax_referer( 'tgajax-post-contact-nonce', 'tg_security' );

	/*
	|--------------------------------------------------------------------------
	| Mailer module
	|--------------------------------------------------------------------------
	|
	| These module are used when sending email from contact form
	|
	*/
	
	//Get your email address
	$contact_email = get_option('pp_contact_email');
	
	//Enter your email address, email from contact form will send to this addresss. Please enter inside quotes ('myemail@email.com')
	define('DEST_EMAIL', $contact_email);
	
	//Change email subject to something more meaningful
	define('SUBJECT_EMAIL', 'Email from contact form');
	
	//Thankyou message when message sent
	define('THANKYOU_MESSAGE', __( 'Thank you! We will get back to you as soon as possible', THEMEDOMAIN ));
	
	//Error message when message can't send
	define('ERROR_MESSAGE', __( 'Oops! something went wrong, please try to submit later.', THEMEDOMAIN ));
	
	
	/*
	|
	| Begin sending mail
	|
	*/
	
	$from_name = $_POST['your_name'];
	$from_email = $_POST['email'];
	
	$headers = "";
   	$headers.= 'From: '.$from_name.'<'.$from_email.'>'.PHP_EOL;
   	$headers.= 'Reply-To: '.$from_name.'<'.$from_email.'>'.PHP_EOL;
   	$headers.= 'Return-Path: '.$from_name.'<'.$from_email.'>'.PHP_EOL;        // these two to set reply address
   	$headers.= "Message-ID: <".time()."webmaster@".$_SERVER['SERVER_NAME'].">".PHP_EOL;
   	$headers.= "X-Mailer: PHP v".phpversion().PHP_EOL;                  // These two to help avoid spam-filters
	
	$message = 'Name: '.$from_name.PHP_EOL;
	$message.= 'Email: '.$from_email.PHP_EOL.PHP_EOL;
	$message.= 'Message: '.PHP_EOL.$_POST['message'];
	
	if(isset($_POST['address']))
	{
		$message.= 'Address: '.$_POST['address'].PHP_EOL;
	}
	
	if(isset($_POST['phone']))
	{
		$message.= 'Phone: '.$_POST['phone'].PHP_EOL;
	}
	
	if(isset($_POST['mobile']))
	{
		$message.= 'Mobile: '.$_POST['mobile'].PHP_EOL;
	}
	
	if(isset($_POST['company']))
	{
		$message.= 'Company: '.$_POST['company'].PHP_EOL;
	}
	
	if(isset($_POST['country']))
	{
		$message.= 'Country: '.$_POST['country'].PHP_EOL;
	}
	    
	
	if(!empty($from_name) && !empty($from_email) && !empty($message))
	{
		mail(DEST_EMAIL, SUBJECT_EMAIL, $message, $headers);
	
		echo THANKYOU_MESSAGE;
		
		exit;
	}
	else
	{
		echo ERROR_MESSAGE;
		
		exit;
	}
	
	/*
	|
	| End sending mail
	|
	*/
}

?>