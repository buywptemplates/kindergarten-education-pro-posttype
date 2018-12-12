<?php 
/*
 Plugin Name: Kindergarten Education Pro Posttype
 lugin URI: https://www.buywptemplates.com/
 Description: Creating new post type for Kindergarten Education Pro Theme.
 Author: BuyWpTemplates
 Version: 1.0
 Author URI: https://www.buywptemplates.com/
*/

define( 'KINDERGARTEN_EDUCATION_PRO_POSTTYPE_VERSION', '1.0' );
add_action( 'init', 'classescategory');
add_action( 'init', 'kindergarten_education_pro_posttype_create_post_type' );

function kindergarten_education_pro_posttype_create_post_type() {

  register_post_type( 'services',
    array(
        'labels' => array(
            'name' => __( 'Services','kindergarten-education-pro-posttype' ),
            'singular_name' => __( 'Services','kindergarten-education-pro-posttype' )
        ),
        'capability_type' =>  'post',
        'menu_icon'  => 'dashicons-tag',
        'public' => true,
        'supports' => array(
        'title',
        'editor',
        'thumbnail',
        'page-attributes',
        'comments'
        )
    )
  );

  register_post_type( 'classes',
    array(
        'labels' => array(
            'name' => __( 'Classes','kindergarten-education-pro-posttype' ),
            'singular_name' => __( 'Classes','kindergarten-education-pro-posttype' )
        ),
        'capability_type' =>  'post',
        'menu_icon'  => 'dashicons-welcome-learn-more',
        'public' => true,
        'supports' => array(
        'title',
        'editor',
        'thumbnail',
        'page-attributes',
        'comments'
        )
    )
  );
  register_post_type( 'events',
    array(
        'labels' => array(
            'name' => __( 'Events','kindergarten-education-pro-posttype' ),
            'singular_name' => __( 'Events','kindergarten-education-pro-posttype' )
        ),
        'capability_type' =>  'post',
        'menu_icon'  => 'dashicons-tag',
        'public' => true,
        'supports' => array(
        'title',
        'editor',
        'thumbnail',
        'page-attributes',
        'comments'
        )
    )
  );
  register_post_type( 'testimonials',
    array(
      'labels' => array(
        'name' => __( 'Testimonials','kindergarten-education-pro-posttype' ),
        'singular_name' => __( 'Testimonials','kindergarten-education-pro-posttype' )
      ),
      'capability_type' => 'post',
      'menu_icon'  => 'dashicons-businessman',
      'public' => true,
      'supports' => array(
        'title',
        'editor',
        'thumbnail'
      )
    )
  );
  register_post_type( 'teachers',
    array(
      'labels' => array(
        'name' => __( 'Teachers','kindergarten-education-pro-posttype' ),
        'singular_name' => __( 'Teachers','kindergarten-education-pro-posttype' )
      ),
        'capability_type' => 'post',
        'menu_icon'  => 'dashicons-businessman',
        'public' => true,
        'supports' => array( 
          'title',
          'editor',
          'thumbnail'
      )
    )
  );
}

// ------------------- Services -----------------------

function kindergarten_education_pro_posttype_images_metabox_enqueue($hook) {
  if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
    wp_enqueue_script('kindergarten-education-pro-posttype-images-metabox', plugin_dir_url( __FILE__ ) . '/js/img-metabox.js', array('jquery', 'jquery-ui-sortable'));

    global $post;
    if ( $post ) {
      wp_enqueue_media( array(
          'post' => $post->ID,
        )
      );
    }

  }
}
add_action('admin_enqueue_scripts', 'kindergarten_education_pro_posttype_images_metabox_enqueue');
// Services Meta
function kindergarten_education_pro_posttype_bn_custom_meta_services() {

    add_meta_box( 'bn_meta', __( 'Services Meta', 'kindergarten-education-pro-posttype' ), 'kindergarten_education_pro_posttype_bn_meta_callback_services', 'services', 'normal', 'high' );
}
/* Hook things in for admin*/
if (is_admin()){
  add_action('admin_menu', 'kindergarten_education_pro_posttype_bn_custom_meta_services');
}

function kindergarten_education_pro_posttype_bn_meta_callback_services( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );
    ?>
  <div id="property_stuff">
    <table id="list-table">     
      <tbody id="the-list" data-wp-lists="list:meta">
        <tr id="meta-1">
          <p>
            <label for="meta-image"><?php echo esc_html('Icon Image'); ?></label><br>
            <input type="text" name="meta-image" id="meta-image" class="meta-image regular-text" value="<?php echo $bn_stored_meta['meta-image']; ?>">
            <input type="button" class="button image-upload" value="Browse">
          </p>
          <div class="image-preview"><img src="<?php echo $bn_stored_meta['meta-image']; ?>" style="max-width: 250px;"></div>
        </tr>
        
      </tbody>
    </table>
  </div>
  <?php
}

function kindergarten_education_pro_posttype_bn_meta_save_services( $post_id ) {



  if (!isset($_POST['bn_nonce']) || !wp_verify_nonce($_POST['bn_nonce'], basename(__FILE__))) {
    return;
  }

  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }
  // Save Image
  if( isset( $_POST[ 'meta-image' ] ) ) {
      update_post_meta( $post_id, 'meta-image', esc_url_raw($_POST[ 'meta-image' ]) );
  }
  if( isset( $_POST[ 'meta-url' ] ) ) {
      update_post_meta( $post_id, 'meta-url', esc_url_raw($_POST[ 'meta-url' ]) );
  }
}
add_action( 'save_post', 'kindergarten_education_pro_posttype_bn_meta_save_services' );

/* Services shortcode */
function kindergarten_education_pro_posttype_services_func( $atts ) {
  $services = '';
  $services = '<div class="row all-services">';
  $query = new WP_Query( array( 'post_type' => 'services') );

    if ( $query->have_posts() ) :

  $k=1;
  $new = new WP_Query('post_type=services');
  while ($new->have_posts()) : $new->the_post();
        $custom_url ='';
        $post_id = get_the_ID();
        $excerpt = wp_trim_words(get_the_excerpt(),25);
        $services_image= get_post_meta(get_the_ID(), 'meta-image', true);
        if(get_post_meta($post_id,'meta-services-url',true !='')){$custom_url =get_post_meta($post_id,'meta-services-url',true); } else{ $custom_url = get_permalink(); }
        $services .= '

            <div class="our_services_outer col-md-6 col-sm-6">
              <div class="services_inner">
                <div class="row hover_border">
                  <div class="col-md-3 pra-img-box">
                     <a href="'.esc_url($custom_url).'"><img src="'.esc_url($services_image).'" class="pra-img"></a>
                  </div>
                  <div class="col-md-9">
                    <h4 class="mt-0 pra-title"> <a href="'.esc_url($custom_url).'">'.esc_html(get_the_title()) .'</a></h4>
                    <div class="short_text">'.$excerpt.'</div>
                  </div>
                </div>
              </div>
            </div>';
    if($k%2 == 0){
      $services.= '<div class="clearfix"></div>';
    }
      $k++;
  endwhile;
  else :
    $services = '<h2 class="center">'.esc_html__('Post Not Found','kindergarten_education_pro_posttype').'</h2>';
  endif;
  return $services;
}

add_shortcode( 'list-services', 'kindergarten_education_pro_posttype_services_func' );


// ------------------ Classes --------------------


function kindergarten_education_pro_posttype_bn_classes_meta() {
    add_meta_box( 'kindergarten_education_pro_posttype_bn_meta', __( 'Enter Classes Details','kindergarten-education-pro-posttype' ), 'kindergarten_education_pro_posttype_bn_meta_classes', 'classes', 'normal', 'high' );
}
// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'kindergarten_education_pro_posttype_bn_classes_meta');
}
/* Adds a meta box for custom post */
function kindergarten_education_pro_posttype_bn_meta_classes( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'kindergarten_education_pro_posttype_bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );
    ?>
    <div id="courses_custom_stuff">
        <table id="list-table">         
            <tbody id="the-list" data-wp-lists="list:meta">
                <tr id="meta-7">
                  <td class="left">
                    <?php esc_html_e( 'Age', 'kindergarten-education-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="text" name="meta-tutor" id="meta-tutor" value="<?php echo esc_html($bn_stored_meta['meta-tutor'][0]); ?>" />
                  </td>
                </tr>
                <tr id="meta-9">
                  <td class="left">
                    <?php esc_html_e( 'Class Size', 'kindergarten-education-pro-posttype' )?>
                  </td>
                  <td class="left" >
                     <input type="text" name="meta-size" id="meta-size" value="<?php echo esc_html($bn_stored_meta['meta-size'][0]); ?>" />
                  </td>
                </tr>
                <tr id="meta-2">
                    <td class="left">
                        <?php esc_html_e( 'Price', 'kindergarten-education-pro-posttype' )?>
                    </td>
                    <td class="left" >
                        <input type="number" name="meta-price" id="meta-price" value="<?php echo esc_html($bn_stored_meta['meta-price'][0]); ?>" />
                    </td>
                </tr> 
                <tr id="meta-2">
                    <td class="left">
                        <?php esc_html_e( 'Duration', 'kindergarten-education-pro-posttype' )?>
                    </td>
                    <td class="left" >
                        <input type="text" name="meta-durations1" id="meta-durations1" value="<?php echo esc_html($bn_stored_meta['meta-durations1'][0]); ?>" />
                    </td>
                </tr>              
                <tr id="meta-2">
                    <td class="left">
                        <?php esc_html_e( 'Instructor', 'kindergarten-education-pro-posttype' )?>
                    </td>
                    <td class="left" >
                        <input type="text" name="meta-instructor" id="meta-instructor" value="<?php echo esc_html($bn_stored_meta['meta-instructor'][0]); ?>" />
                    </td>
                </tr> 
                <tr id="meta-2">
                    <td class="left">
                        <?php esc_html_e( 'Class Schedule', 'kindergarten-education-pro-posttype' )?>
                    </td>
                    <td class="left" >
                        <input type="text" name="meta-class_schedule" id="meta-class_schedule" value="<?php echo esc_html($bn_stored_meta['meta-class_schedule'][0]); ?>" />
                    </td>
                </tr>  
                <tr id="meta-2">
                    <td class="left">
                        <?php esc_html_e( 'Class Time', 'kindergarten-education-pro-posttype' )?>
                    </td>
                    <td class="left" >
                        <input type="text" name="meta-class_time" id="meta-class_time" value="<?php echo esc_html($bn_stored_meta['meta-class_time'][0]); ?>" />
                    </td>
                </tr>     
            </tbody>
        </table>
    </div>
    <?php
}
/* Saves the custom fields meta input */
function kindergarten_education_pro_posttype_bn_metadesig_classes_save( $post_id ) {
  
    if( isset( $_POST[ 'meta-price' ] ) ) {
        update_post_meta( $post_id, 'meta-price', sanitize_text_field($_POST[ 'meta-price' ]) );
    }
    if( isset( $_POST[ 'meta-tutor' ] ) ) {
        update_post_meta( $post_id, 'meta-tutor', sanitize_text_field($_POST[ 'meta-tutor' ]) );
    }
    if( isset( $_POST[ 'meta-starts' ] ) ) {
        update_post_meta( $post_id, 'meta-starts', sanitize_text_field($_POST[ 'meta-starts' ]) );
    }
    if( isset( $_POST[ 'meta-size' ] ) ) {
        update_post_meta( $post_id, 'meta-size', sanitize_text_field($_POST[ 'meta-size' ]) );
    }
    if( isset( $_POST[ 'meta-durations1' ] ) ) {
        update_post_meta( $post_id, 'meta-durations1', sanitize_text_field($_POST[ 'meta-durations1' ]) );
    }
    if( isset( $_POST[ 'meta-instructor' ] ) ) {
        update_post_meta( $post_id, 'meta-instructor', sanitize_text_field($_POST[ 'meta-instructor' ]) );
    }
    if( isset( $_POST[ 'meta-class_schedule' ] ) ) {
        update_post_meta( $post_id, 'meta-class_schedule', sanitize_text_field($_POST[ 'meta-class_schedule' ]) );
    }
    if( isset( $_POST[ 'meta-class_time' ] ) ) {
        update_post_meta( $post_id, 'meta-class_time', sanitize_text_field($_POST[ 'meta-class_time' ]) );
    }
    
}
add_action( 'save_post', 'kindergarten_education_pro_posttype_bn_metadesig_classes_save' );

/* Classes shortcode */
function kindergarten_education_pro_posttype_classes_func( $atts ) {
  $classes = '';
  $classes = '<div class="row all-classes">';
  $query = new WP_Query( array( 'post_type' => 'classes') );

    if ( $query->have_posts() ) :

  $k=1;
  $new = new WP_Query('post_type=classes');
  while ($new->have_posts()) : $new->the_post();

        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
        if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
        $url = $thumb['0'];
        $custom_url ='';
        $post_id = get_the_ID();
        $excerpt = wp_trim_words(get_the_excerpt(),25);
        $age= get_post_meta($post_id,'meta-tutor',true);
        $size= get_post_meta($post_id,'meta-size',true);
        $price= get_post_meta($post_id,'meta-price',true);
        
        if(get_post_meta($post_id,'meta-classes-url',true !='')){$custom_url =get_post_meta($post_id,'meta-classes-url',true); } else{ $custom_url = get_permalink(); }
        $classes .= '

            <div class="our_classes_outer col-lg-4 col-md-4 col-sm-6">
              <div class="classes_inner">
                <div class="row hover_border">
                  <div class="col-lg-12 classes-img-box">
                    <img class="classes-img" src="'.esc_url($thumb_url).'" alt="attorney-thumbnail" />
                    <h5><a href="'.esc_url($custom_url).'">'.esc_html(get_the_title()) .'</a></h5>
                    <div class="short_text">'.$excerpt.'</div>
                  </div>
                </div>
                <div class="row inner-classes-meta">
                  <div class="col-lg-4 col-md-4">
                    <span>Year Old</span>
                    <p>'.$age.'</p>
                  </div>
                  <div class="col-lg-4 col-md-4">
                    <span>Class Size</span>
                    <p>'.$size.'</p>
                  </div>
                  <div class="col-lg-4 col-md-4">
                    <span>m/h</span>
                    <p>'.$price.'</p>
                  </div>
                </div>
              </div>
            </div>';
    if($k%2 == 0){
      $classes.= '<div class="clearfix"></div>';
    }
      $k++;
  endwhile;
  else :
    $classes = '<h2 class="center">'.esc_html__('Post Not Found','kindergarten_education_pro_posttype').'</h2>';
  endif;
  return $classes;
}

add_shortcode( 'classes', 'kindergarten_education_pro_posttype_classes_func' );


// --------------------------- Events --------------------------

// Events Meta
function kindergarten_education_pro_posttype_bn_custom_meta_events() {

    add_meta_box( 'bn_meta', __( 'Events Meta', 'kindergarten-education-pro-posttype' ), 'kindergarten_education_pro_posttype_bn_meta_callback_events', 'events', 'normal', 'high' );
}
/* Hook things in for admin*/
if (is_admin()){
  add_action('admin_menu', 'kindergarten_education_pro_posttype_bn_custom_meta_events');
}

function kindergarten_education_pro_posttype_bn_meta_callback_events( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );
    ?>
  <div id="property_stuff">
    <table id="list-table">     
      <tbody id="the-list" data-wp-lists="list:meta">
        <tr id="meta-9">
          <td class="left">
            <?php esc_html_e( 'Event Date', 'kindergarten-education-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="text" name="events-date" id="events-date" class="meta-duration regular-text" value="<?php echo $bn_stored_meta['events-date'][0]; ?>">
          </td>
        </tr>
        
      </tbody>
    </table>
  </div>
  <?php
}

function kindergarten_education_pro_posttype_bn_meta_save_events( $post_id ) {



  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }
  if( isset( $_POST[ 'events-date' ] ) ) {
        update_post_meta( $post_id, 'events-date', sanitize_text_field($_POST[ 'events-date' ]) );
  }
  
}
add_action( 'save_post', 'kindergarten_education_pro_posttype_bn_meta_save_events' );


/* Events shortcode */
function kindergarten_education_pro_posttype_events_func( $atts ) {
  $events = '';
  $events = '<div class="row all-events">';
  $query = new WP_Query( array( 'post_type' => 'events') );

    if ( $query->have_posts() ) :

  $k=1;
  $new = new WP_Query('post_type=events');
  while ($new->have_posts()) : $new->the_post();
         $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
        if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
        $url = $thumb['0'];
        $custom_url ='';
        $post_id = get_the_ID();
        $excerpt = wp_trim_words(get_the_excerpt(),25);
        $events_date= get_post_meta($post_id,'events-date',true);
        if(get_post_meta($post_id,'meta-events-url',true !='')){$custom_url =get_post_meta($post_id,'meta-events-url',true); } else{ $custom_url = get_permalink(); }
        $events .= '

            <div class="our_events_outer col-lg-4 col-md-4 col-sm-6">
              <div class="events_inner">
                <div class="row hover_border">
                  <div class="col-md-12 pra-img-box">
                     <img class="classes-img" src="'.esc_url($thumb_url).'" alt="attorney-thumbnail" />
                     <div class="eve-date">'.$events_date.'</div>
                    <h5><a href="'.esc_url($custom_url).'">'.esc_html(get_the_title()) .'</a></h5>
                    <div class="short_text">'.$excerpt.'</div>
                  </div>
                </div>
              </div>
            </div>';
    if($k%2 == 0){
      $events.= '<div class="clearfix"></div>';
    }
      $k++;
  endwhile;
  else :
    $events = '<h2 class="center">'.esc_html__('Post Not Found','kindergarten_education_pro_posttype').'</h2>';
  endif;
  return $events;
}

add_shortcode( 'events', 'kindergarten_education_pro_posttype_events_func' );


/*---------------------------------- Testimonial section -------------------------------------*/
/* Adds a meta box to the Testimonial editing screen */
function kindergarten_education_pro_posttype_bn_testimonial_meta_box() {
  add_meta_box( 'kindergarten-education-pro-posttype-testimonial-meta', __( 'Enter Details', 'kindergarten-education-pro-posttype' ), 'kindergarten_education_pro_posttype_bn_testimonial_meta_callback', 'testimonials', 'normal', 'high' );
}
// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'kindergarten_education_pro_posttype_bn_testimonial_meta_box');
}

/* Adds a meta box for custom post */
function kindergarten_education_pro_posttype_bn_testimonial_meta_callback( $post ) {
  wp_nonce_field( basename( __FILE__ ), 'kindergarten_education_pro_posttype_posttype_testimonial_meta_nonce' );
  $bn_stored_meta = get_post_meta( $post->ID );
  $desigstory = get_post_meta( $post->ID, 'kindergarten_education_pro_posttype_testimonial_desigstory', true );
  ?>
  <div id="testimonials_custom_stuff">
    <table id="list">
      <tbody id="the-list" data-wp-lists="list:meta">
        <tr id="meta-1">
          <td class="left">
            <?php _e( 'Designation', 'kindergarten-education-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="text" name="kindergarten_education_pro_posttype_testimonial_desigstory" id="kindergarten_education_pro_posttype_testimonial_desigstory" value="<?php echo esc_attr( $desigstory ); ?>" />
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <?php
}

/* Saves the custom meta input */
function kindergarten_education_pro_posttype_bn_metadesig_save( $post_id ) {
  if (!isset($_POST['kindergarten_education_pro_posttype_posttype_testimonial_meta_nonce']) || !wp_verify_nonce($_POST['kindergarten_education_pro_posttype_posttype_testimonial_meta_nonce'], basename(__FILE__))) {
    return;
  }

  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }

  // Save desig.
  if( isset( $_POST[ 'kindergarten_education_pro_posttype_testimonial_desigstory' ] ) ) {
    update_post_meta( $post_id, 'kindergarten_education_pro_posttype_testimonial_desigstory', sanitize_text_field($_POST[ 'kindergarten_education_pro_posttype_testimonial_desigstory']) );
  }

}

add_action( 'save_post', 'kindergarten_education_pro_posttype_bn_metadesig_save' );

/*---------------------------------- Testimonials shortcode --------------------------------------*/
function kindergarten_education_pro_posttype_testimonial_func( $atts ) {
  $testimonial = '';
  $testimonial = '<div class="row all-testimonial">';
  $query = new WP_Query( array( 'post_type' => 'testimonials') );

    if ( $query->have_posts() ) :

  $k=1;
  $new = new WP_Query('post_type=testimonials');
  while ($new->have_posts()) : $new->the_post();
         $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
        if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
        $url = $thumb['0'];
        $custom_url ='';
        $post_id = get_the_ID();
        $excerpt = wp_trim_words(get_the_excerpt(),25);
        $tdegignation= get_post_meta($post_id,'kindergarten_education_pro_posttype_testimonial_desigstory',true);
        if(get_post_meta($post_id,'meta-testimonial-url',true !='')){$custom_url =get_post_meta($post_id,'meta-testimonial-url',true); } else{ $custom_url = get_permalink(); }
        $testimonial .= '

            <div class="our_testimonial_outer col-lg-4 col-md-4 col-sm-6">
              <div class="testimonial_inner">
                <div class="row hover_border">
                  <div class="col-md-12 pra-img-box">
                     <img class="classes-img" src="'.esc_url($thumb_url).'" alt="attorney-thumbnail" />
                    <h5><a href="'.esc_url($custom_url).'">'.esc_html(get_the_title()) .'</a></h5>
                    <div class="tdesig">'.$tdegignation.'</div>
                    <div class="short_text">'.$excerpt.'</div>
                  </div>
                </div>
              </div>
            </div>';
    if($k%2 == 0){
      $testimonial.= '<div class="clearfix"></div>';
    }
      $k++;
  endwhile;
  else :
    $testimonial = '<h2 class="center">'.esc_html__('Post Not Found','kindergarten_education_pro_posttype').'</h2>';
  endif;
  return $testimonial;
}

add_shortcode( 'testimonial', 'kindergarten_education_pro_posttype_testimonial_func' );

/*-------------------------------------- Teacher-------------------------------------------*/
/* Adds a meta box for Designation */
function kindergarten_education_pro_posttype_bn_teachers_meta() {
    add_meta_box( 'kindergarten_education_pro_posttype_bn_meta', __( 'Enter Details','kindergarten-education-pro-posttype' ), 'kindergarten_education_pro_posttype_ex_bn_meta_callback', 'teachers', 'normal', 'high' );
}
// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'kindergarten_education_pro_posttype_bn_teachers_meta');
}
/* Adds a meta box for custom post */
function kindergarten_education_pro_posttype_ex_bn_meta_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'kindergarten_education_pro_posttype_bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );

    //Email details
    if(!empty($bn_stored_meta['meta-desig'][0]))
      $bn_meta_desig = $bn_stored_meta['meta-desig'][0];
    else
      $bn_meta_desig = '';

    //Phone details
    if(!empty($bn_stored_meta['meta-call'][0]))
      $bn_meta_call = $bn_stored_meta['meta-call'][0];
    else
      $bn_meta_call = '';


    //facebook details
    if(!empty($bn_stored_meta['meta-tfacebookurl'][0]))
      $bn_meta_facebookurl = $bn_stored_meta['meta-tfacebookurl'][0];
    else
      $bn_meta_facebookurl = '';


    //linkdenurl details
    if(!empty($bn_stored_meta['meta-tlinkdenurl'][0]))
      $bn_meta_linkdenurl = $bn_stored_meta['meta-tlinkdenurl'][0];
    else
      $bn_meta_linkdenurl = '';

    //twitterurl details
    if(!empty($bn_stored_meta['meta-ttwitterurl'][0]))
      $bn_meta_twitterurl = $bn_stored_meta['meta-ttwitterurl'][0];
    else
      $bn_meta_twitterurl = '';

    //twitterurl details
    if(!empty($bn_stored_meta['meta-tgoogleplusurl'][0]))
      $bn_meta_googleplusurl = $bn_stored_meta['meta-tgoogleplusurl'][0];
    else
      $bn_meta_googleplusurl = '';

    //twitterurl details
    if(!empty($bn_stored_meta['meta-designation'][0]))
      $bn_meta_designation = $bn_stored_meta['meta-designation'][0];
    else
      $bn_meta_designation = '';

    ?>
  
    <div id="agent_custom_stuff">
        <table id="list-table">         
            <tbody id="the-list" data-wp-lists="list:meta">
                <tr id="meta-1">
                    <td class="left">
                        <?php _e( 'Email', 'kindergarten-education-pro-posttype' )?>
                    </td>
                    <td class="left" >
                        <input type="text" name="meta-desig" id="meta-desig" value="<?php echo esc_attr($bn_meta_desig); ?>" />
                    </td>
                </tr>
                <tr id="meta-2">
                    <td class="left">
                        <?php _e( 'Phone Number', 'kindergarten-education-pro-posttype' )?>
                    </td>
                    <td class="left" >
                        <input type="text" name="meta-call" id="meta-call" value="<?php echo esc_attr($bn_meta_call); ?>" />
                    </td>
                </tr>
                <tr id="meta-3">
                  <td class="left">
                    <?php _e( 'Facebook Url', 'kindergarten-education-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-tfacebookurl" id="meta-tfacebookurl" value="<?php echo $bn_stored_meta['meta-tfacebookurl'][0]; ?>" />
                  </td>
                </tr>
                <tr id="meta-4">
                  <td class="left">
                    <?php _e( 'Linkedin URL', 'kindergarten-education-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-tlinkdenurl" id="meta-tlinkdenurl" value="<?php echo $bn_stored_meta['meta-tlinkdenurl'][0]; ?>" />
                  </td>
                </tr>
                <tr id="meta-5">
                  <td class="left">
                    <?php _e( 'Twitter Url', 'kindergarten-education-pro-posttype' ); ?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-ttwitterurl" id="meta-ttwitterurl" value="<?php echo $bn_stored_meta['meta-ttwitterurl'][0]; ?>" />
                  </td>
                </tr>
                <tr id="meta-6">
                  <td class="left">
                    <?php _e( 'GooglePlus URL', 'kindergarten-education-pro-posttype' ); ?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-tgoogleplusurl" id="meta-tgoogleplusurl" value="<?php echo $bn_stored_meta['meta-tgoogleplusurl'][0]; ?>" />
                  </td>
                </tr>
                <tr id="meta-7">
                  <td class="left">
                    <?php _e( 'Designation', 'kindergarten-education-pro-posttype' ); ?>
                  </td>
                  <td class="left" >
                    <input type="text" name="meta-designation" id="meta-designation" value="<?php echo esc_attr($bn_meta_designation); ?>" />
                  </td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php
}
/* Saves the custom Designation meta input */
function kindergarten_education_pro_posttype_ex_bn_metadesig_save( $post_id ) {
    if( isset( $_POST[ 'meta-desig' ] ) ) {
        update_post_meta( $post_id, 'meta-desig', esc_html($_POST[ 'meta-desig' ]) );
    }
    if( isset( $_POST[ 'meta-call' ] ) ) {
        update_post_meta( $post_id, 'meta-call', esc_html($_POST[ 'meta-call' ]) );
    }
    // Save facebookurl
    if( isset( $_POST[ 'meta-tfacebookurl' ] ) ) {
        update_post_meta( $post_id, 'meta-tfacebookurl', esc_url($_POST[ 'meta-tfacebookurl' ]) );
    }
    // Save linkdenurl
    if( isset( $_POST[ 'meta-tlinkdenurl' ] ) ) {
        update_post_meta( $post_id, 'meta-tlinkdenurl', esc_url($_POST[ 'meta-tlinkdenurl' ]) );
    }
    if( isset( $_POST[ 'meta-ttwitterurl' ] ) ) {
        update_post_meta( $post_id, 'meta-ttwitterurl', esc_url($_POST[ 'meta-ttwitterurl' ]) );
    }
    // Save googleplusurl
    if( isset( $_POST[ 'meta-tgoogleplusurl' ] ) ) {
        update_post_meta( $post_id, 'meta-tgoogleplusurl', esc_url($_POST[ 'meta-tgoogleplusurl' ]) );
    }
    // Save designation
    if( isset( $_POST[ 'meta-designation' ] ) ) {
        update_post_meta( $post_id, 'meta-designation', esc_html($_POST[ 'meta-designation' ]) );
    }
}
add_action( 'save_post', 'kindergarten_education_pro_posttype_ex_bn_metadesig_save' );

add_action( 'save_post', 'bn_meta_save' );
/* Saves the custom meta input */
function bn_meta_save( $post_id ) {
  if( isset( $_POST[ 'kindergarten_education_pro_posttype_teachers_featured' ] )) {
      update_post_meta( $post_id, 'kindergarten_education_pro_posttype_teachers_featured', esc_attr(1));
  }else{
    update_post_meta( $post_id, 'kindergarten_education_pro_posttype_teachers_featured', esc_attr(0));
  }
}
/*------------------------------------- SHORTCODES -------------------------------------*/

/*------------------------------------- Teachers Shorthcode -------------------------------------*/
function kindergarten_education_pro_posttype_teachers_func( $atts ) {
  $teachers = '';
  $teachers = '<div class="row all-teachers">';
  $query = new WP_Query( array( 'post_type' => 'teachers') );

    if ( $query->have_posts() ) :

  $k=1;
  $new = new WP_Query('post_type=teachers');
  while ($new->have_posts()) : $new->the_post();
         $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
        if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
        $url = $thumb['0'];
        $custom_url ='';
        $post_id = get_the_ID();
        $excerpt = wp_trim_words(get_the_excerpt(),25);
        $teachers_desig= get_post_meta($post_id,'meta-designation',true);
        $facebookurl= get_post_meta($post_id,'meta-tfacebookurl',true);
        $linkedin=get_post_meta($post_id,'meta-tlinkdenurl',true);
        $twitter=get_post_meta($post_id,'meta-ttwitterurl',true);
        $googleplus=get_post_meta($post_id,'meta-tgoogleplusurl',true);
        if(get_post_meta($post_id,'meta-teachers-url',true !='')){$custom_url =get_post_meta($post_id,'meta-teachers-url',true); } else{ $custom_url = get_permalink(); }
        $teachers .= '

            <div class="our_teachers_outer col-lg-4 col-md-4 col-sm-6">
              <div class="teachers_inner">
                <div class="row hover_border">
                  <div class="col-md-12 pra-img-box">
                     <img class="classes-img" src="'.esc_url($thumb_url).'" alt="attorney-thumbnail" />
                     <div class="tdesig">'.$teachers_desig.'</div>
                    <h5><a href="'.esc_url($custom_url).'">'.esc_html(get_the_title()) .'</a></h5>
                    <div class="short_text">'.$excerpt.'</div>
                    <div class="att_socialbox">';
                        if($facebookurl != ''){
                          $teachers .= '<a class="" href="'.esc_url($facebookurl).'" target="_blank"><i class="fab fa-facebook-f"></i></a>';
                        } if($twitter != ''){
                          $teachers .= '<a class="" href="'.esc_url($twitter).'" target="_blank"><i class="fab fa-twitter"></i></a>';
                        } if($googleplus != ''){
                          $teachers .= '<a class="" href="'.esc_url($googleplus).'" target="_blank"><i class="fab fa-google-plus-g"></i></a>';
                        } if($linkedin != ''){
                          $teachers .= '<a class="" href="'.esc_url($linkedin).'" target="_blank"><i class="fab fa-linkedin-in"></i></a>';
                        }
                      $teachers .= '</div>
                  </div>
                </div>
              </div>
            </div>';
    if($k%2 == 0){
      $teachers.= '<div class="clearfix"></div>';
    }
      $k++;
  endwhile;
  else :
    $teachers = '<h2 class="center">'.esc_html__('Post Not Found','kindergarten_education_pro_posttype').'</h2>';
  endif;
  return $teachers;
}

add_shortcode( 'teachers', 'kindergarten_education_pro_posttype_teachers_func' );


function classescategory() {
  // Add new taxonomy, make it hierarchical (like categories)
  $labels = array(
    'name'              => __( 'Categories', 'kindergarten-education-pro-posttype' ),
    'singular_name'     => __( 'Categories', 'kindergarten-education-pro-posttype' ),
    'search_items'      => __( 'Search cats', 'kindergarten-education-pro-posttype' ),
    'all_items'         => __( 'All Categories', 'kindergarten-education-pro-posttype' ),
    'parent_item'       => __( 'Parent Categories', 'kindergarten-education-pro-posttype' ),
    'parent_item_colon' => __( 'Parent Categories:', 'kindergarten-education-pro-posttype' ),
    'edit_item'         => __( 'Edit Categories', 'kindergarten-education-pro-posttype' ),
    'update_item'       => __( 'Update Categories', 'kindergarten-education-pro-posttype' ),
    'add_new_item'      => __( 'Add New Categories', 'kindergarten-education-pro-posttype' ),
    'new_item_name'     => __( 'New Categories Name', 'kindergarten-education-pro-posttype' ),
    'menu_name'         => __( 'Categories', 'kindergarten-education-pro-posttype' ),
  );
  $args = array(
    'hierarchical'      => true,
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array( 'slug' => 'classescategory' ),
  );
  register_taxonomy( 'classescategory', array( 'classes' ), $args );
}