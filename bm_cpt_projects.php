<?php
/*
Plugin Name: Simple portfolio 
Plugin URI: https://github.com/bastetmilo/bm-simple-portfolio-plugin
Description: Plugin I wrote to excercise 
Version: 1.0
Author: Kasia 'bastetmilo' Świderska
Author URI: http://hekate-design.pl
License: GPL
*/
 
if ( ! function_exists( 'bm_cpt_projects' ) ) {
 
// register custom post type
    function bm_cpt_projects() {
 
        // these are the labels in the admin interface, edit them as you like
        $labels = array(
            'name'                => _x( 'Projects', 'Post Type General Name', 'bm_projects_cpt' ),
            'singular_name'       => _x( 'Project', 'Post Type Singular Name', 'bm_projects_cpt' ),
            'menu_name'           => __( 'Projects', 'bm_projects_cpt' ),
            'parent_item_colon'   => __( 'Parent:', 'bm_projects_cpt' ),
            'all_items'           => __( 'All', 'bm_projects_cpt' ),
            'view_item'           => __( 'View', 'bm_projects_cpt' ),
            'add_new_item'        => __( 'Add new project', 'bm_projects_cpt' ),
            'add_new'             => __( 'Add new', 'bm_projects_cpt' ),
            'edit_item'           => __( 'Edit', 'bm_projects_cpt' ),
            'update_item'         => __( 'Update', 'bm_projects_cpt' ),
            'search_items'        => __( 'Search', 'bm_projects_cpt' ),
            'not_found'           => __( 'Not found', 'bm_projects_cpt' ),
            'not_found_in_trash'  => __( 'Not found in Trash', 'bm_projects_cpt' ),
        );
        $args = array(
            // use the labels above
            'labels'              => $labels,
            // we'll only need the title, the Visual editor and the excerpt fields for our post type
            'supports'            => array( 'title', 'editor', 'thumbnail'),
            // we're going to create this taxonomy in the next section, but we need to link our post type to it now
            'taxonomies'          => array( 'bm_projects_tax' ),
            // make it public so we can see it in the admin panel and show it in the front-end
            'public'              => true,
            // show the menu item under the Pages item
            'menu_position'       => 20,
            'hierarchical'        => true,
            // show archives, if you don't need the shortcode
            'has_archive'         => true,
            'rewrite'               => array( 'slug' => 'projects' )
        );

        register_post_type( 'bm_projects_cpt', $args );

         
    }
 
    // hook into the 'init' action
    add_action( 'init', 'bm_cpt_projects', 0 );
 
}
 
if ( ! function_exists( 'bm_projects_cpt_tax' ) ) {
 
    // register custom taxonomy
    function bm_projects_cpt_tax() {
 
        // again, labels for the admin panel
        $labels = array(
            'name'                       => _x( 'Projects categories', 'Taxonomy General Name', 'bm_projects_cpt' ),
            'singular_name'              => _x( 'Project category', 'Taxonomy Singular Name', 'bm_projects_cpt' ),
            'menu_name'                  => __( 'Projects categories', 'bm_projects_cpt' ),
            'all_items'                  => __( 'All', 'bm_projects_cpt' ),
            'parent_item'                => __( 'Parent', 'bm_projects_cpt' ),
            'parent_item_colon'          => __( 'Parent:', 'bm_projects_cpt' ),
            'new_item_name'              => __( 'New category', 'bm_projects_cpt' ),
            'add_new_item'               => __( 'Add new category', 'bm_projects_cpt' ),
            'edit_item'                  => __( 'Edit', 'bm_projects_cpt' ),
            'update_item'                => __( 'Update', 'bm_projects_cpt' ),
            'separate_items_with_commas' => __( 'Separate items with commas', 'bm_projects_cpt' ),
            'search_items'               => __( 'Search', 'bm_projects_cpt' ),
            'add_or_remove_items'        => __( 'Add or remove items', 'bm_projects_cpt' ),
            'choose_from_most_used'      => __( 'Choose from the most used items', 'bm_projects_cpt' ),
            'not_found'                  => __( 'Not found', 'bm_projects_cpt' ),
        );
        $args = array(
            // use the labels above
            'labels'                     => $labels,
            // taxonomy should be hierarchial so we can display it like a category section
            'hierarchical'               => true,
            // again, make the taxonomy public (like the post type)
            'public'                     => true,
            'rewrite'               => array( 'slug' => 'project-categories' ),
        );
        // the contents of the array below specifies which post types should the taxonomy be linked to
        register_taxonomy( 'bm_projects_tax', array( 'bm_projects_cpt' ), $args );
 
    }
 
    // hook into the 'init' action
    add_action( 'init', 'bm_projects_cpt_tax', 0 );
 
}
 

if ( ! function_exists('bm_add_metabox')) {
    function bm_add_metabox(){
        add_meta_box('bm_metabox_infos', 'Project info', 'bm_projects_info', 'bm_projects_cpt', 'normal', 'high');
    }        
    add_action( 'add_meta_boxes', 'bm_add_metabox' );
}

if ( ! function_exists('bm_projects_info')) {
function bm_projects_info( $post ) {
    $bm_client_name = get_post_meta($post->ID, '_bm_client_name', true);
    $bm_website_url = get_post_meta($post->ID, '_bm_website_url', true);
    ?>
    <p><label for="bm_client_name"><?php _e('Client','bm_projects_cpt') ?>:</label><br><input name="bm_client_name" size="75" value="<?php echo esc_attr( $bm_client_name ); ?>" type="text"></p>
    <p><label for="bm_website_url"><?php _e('Website URL','bm_projects_cpt') ?>:</label><br><input name="bm_website_url" size="75" value="<?php echo esc_attr( $bm_website_url ); ?>" type="text"></p>

<?php
    } 
}

if (! function_exists('bm_metabox_save')) {
    function bm_metabox_save( $post_id ){
    if (isset($_POST['bm_client_name'] ) ) {
        update_post_meta( $post_id, '_bm_client_name', strip_tags($_POST['bm_client_name'] ) );
        }
    if (isset($_POST['bm_website_url'] ) ) {
        update_post_meta( $post_id, '_bm_website_url', strip_tags($_POST['bm_website_url'] ) );
        }
    }
add_action ('save_post', 'bm_metabox_save');
}


//Adding custom posts to main loop
if ( ! function_exists('bm_add_projects_home')) 
{
    function bm_add_projects_home( $query ) 
    {
        if ( $query->is_home() && $query->is_main_query() ) 
        {
            $post_types = array('post','bm_projects_cpt');
            $query->set('post_type', $post_types );
        }
    }

    add_action( 'pre_get_posts', 'bm_add_projects_home' );
}

class iworks_prefix_title
{
    public function __construct()
    {
        add_filter('the_title', array( $this, 'the_title' ), 10, 2 );
    }
    public static function init()
    {
        new iworks_prefix_title();
    }
    public function the_title($title, $post_ID)
    {

        if (is_single($post_ID) && is_singular('bm_projects_cpt') ) {
            return __('Project: ','bm_projects_cpt') . $title;
        }
        return $title;
    }
}
iworks_prefix_title::init();

// It adds few things after content - post meta and predefinied text from plugin settings page 
class bm_add_info_content
{
    
    public function __construct()
    {
       add_filter( 'the_content', array( $this, 'the_content'), 10, 2 ); 
    }
    public static function init()
    {
        new bm_add_info_content();
    }
    public function the_content($content)
    {
        if (is_singular('bm_projects_cpt')) {
            global $post;
            $bm_client_name = get_post_meta($post->ID, '_bm_client_name', true);
            $bm_website_url = get_post_meta($post->ID, '_bm_website_url', true);
            $bm_text_after  = get_option('bm_contact_text');
            return $content . '<p>' . __('Client name: ', 'bm_projects_cpt') . '<strong>' . $bm_client_name . '</strong></p><p>' . __('Website URL: ','bm_projects_cpt') . '<a href="' . $bm_website_url . '">' . $bm_website_url . '</a></p><p><strong>' . $bm_text_after . '</strong></p>';

        }
        return $content;
    }
}

bm_add_info_content::init();

// Adding plugin settings page
class bm_add_contact_info
{
    public function __construct()
    {
        add_action('admin_menu', array( $this, 'admin_menu') );
    }
    public static function init()
    {
        new bm_add_contact_info();
    }
    public function admin_menu() 
    {
        add_options_page(__('Portfolio settings','bm_projects_cpt'), __('Portfolio settings','bm_projects_cpt'), 'edit_themes', basename(__FILE__), array( $this, 'bm_projects_options_page') );
    }
    public function bm_project_info_update()
    {
        update_option('bm_contact_text',   $_POST['bm_contact_text']);
    }
    public function bm_projects_options_page() 
    {
        if ( $_POST['update_contact_info'] == 'true' ) { bm_add_contact_info::bm_project_info_update(); }
        ?>
        <div class="wrap">
        <div id="icon-themes" class="icon32"><br /></div>
            <h2><?php _e('Contact info for portfolio','bm_projects_cpt'); ?></h2>
            <form method="POST" action="">
                <input type="hidden" name="update_contact_info" value="true" />
                <p>
                    <label><?php _e('Your text:','bm_projects_cpt') ?></label>
                    <input type="text" name="bm_contact_text" id="bm_contact_text" size="32" value="<?php echo get_option('bm_contact_text'); ?>"/>
                </p>
                <p>
                    <input type="submit" name="save" value="<?php _e('Save','bm_projects_cpt'); ?>" class="button" />
                </p>
            </form>
        </div>
        <?
    }
}
bm_add_contact_info::init();

function bm_cpt_activate() {
    bm_cpt_projects();
    flush_rewrite_rules();
}
 
register_activation_hook( __FILE__, 'bm_cpt_activate' );
 
function bm_cpt_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'bm_cpt_deactivate' );

 
?>