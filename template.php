<?php

/**
 * @file
 * This file is empty by default because the base theme chain (Alpha & Omega) provides
 * all the basic functionality. However, in case you wish to customize the output that Drupal
 * generates through Alpha & Omega this file is a good place to do so.
 * 
 * Alpha comes with a neat solution for keeping this file as clean as possible while the code
 * for your subtheme grows. Please read the README.txt in the /preprocess and /process subfolders
 * for more information on this topic.
 */
 
 
/**
* Override or insert variables into the page template.

function mothershipD7_process_page(&$vars) {
  // Hook into color.module.
  if (module_exists('color')) {
    _color_page_alter($vars);
  }
}
*/
/**
* Override or insert variables into the page template for HTML output.

function mothershipD7_process_html(&$vars) {
  // Hook into color.module.
  if (module_exists('color')) {
    _color_html_alter($vars);
  }
}
*/
 
 
function mothershipD7_preprocess_html(&$variables) {  
  $bg_color = 'teal';
  
  if(theme_get_setting('site_colour')){
    $bg_color = theme_get_setting('site_colour');
  }

  //panels_node
  $variables["classes_array"][] = $bg_color;
  //$variables["classes_array"][] = 'green-panel';
  if (arg(0) == 'node' && is_numeric(arg(1))) {
  	$nodeid = arg(1);
  	$node_info = node_load($nodeid);
  }  
  if(isset($variables['node']->panels_node) && isset($variables['node']->panels_node['css_id'])){
  	$variables['classes_array'][] = $variables['node']->panels_node['css_id'];
  }
  
  $variables["attributes_array"]["class"][] = $bg_color; // For Omega based themes
    
  if( isset($node_info) && isset( $node_info->panels_node ) ){
	$variables["attributes_array"]["class"][] = $node_info->panels_node['css_id'];
  }
  
  
}

function mothershipD7_preprocess_node(&$variables) {  
  if($variables['view_mode'] == 'teaser') {
    $variables['theme_hook_suggestions'][] = 'node__' . $variables['node']->type . '__teaser';   
    $variables['theme_hook_suggestions'][] = 'node__' . $variables['node']->nid . '__teaser';
  }
     
  if ($variables['view_mode'] != 'rss') {
    if ($variables['type'] == 'blog' ) {

      unset($variables['content']['links']['blog']['#links']['blog_usernames_blog']);  //remove user's blog link
      // unset($variables['content']['links']['node']['#links']['node-readmore']);
    }
  }
  
  
}



/* Custom class for the mothership D7 theme - add class for block location and configuration 
  so user don't need to add additional class after applying my mothership theme.
*/
function mothershipD7_preprocess_block(&$variables) {
   
  if ($variables['block']->module == 'ird_webteam_utils' && $variables['block']->delta == 'dynamic_copyright'){
    $variables['attributes_array']['class'][] = 'float-right';    
    $variables['attributes_array']['class'][] = 'copyright-block';
  }
  
  // Superfish : Dynamic menu 
  if ($variables['block']->module == 'superfish' && $variables['block']->delta == 1){
    $variables['attributes_array']['class'][] = 'global-main-intranet-nav';
  }
  
  // Menu & menu-footer-menu
  if ($variables['block']->module == 'menu' && $variables['block']->delta == 'menu-footer-menu'){    
    $variables['attributes_array']['class'][] = 'footer-menu';
  }
  
  // copyright
  if ($variables['block']->module == 'ird_webteam_utils' && $variables['block']->delta == 'dynamic_copyright'){
     $variables['attributes_array']['class'][] = 'copyright-block';
  }
    
  //search & form
  if ($variables['block']->module == 'search' && $variables['block']->delta == 'form'){     
    $variables['attributes_array']['class'][] = 'float-right';
  } 
}


function mothershipD7_process_region(&$vars) {
  if (in_array($vars['elements']['#region'], array('content', 'menu', 'branding', 'footer_first'))) {
    
    $theme = alpha_get_theme();
    
    $date_string = date('Y');  
    $current_year = '<p>Copyright &copy;'.$date_string.' Inland Revenue.</p>';
    $vars['footer_copyright'] = $current_year;
    $vars['linked_logo_img'] = '<ul class="menu"><li class="first leaf"><a class="phone" href="http://intranet.ird.govt.nz/phonebook/">Phone</a></li>
                                      <li class="leaf"><a class="sitemap" href="'.base_path().'sitemap">Sitemap</a></li>
                                      <li class="leaf"><a class="emergency-help" href="http://intranet.ird.govt.nz/cra/guidelines-policies/bcem-accountabilities-and-responsibilities/emergency-help">Emergency help</a></li>
                                      <li class="leaf"><a class="archive" href="http://intranet.ird.govt.nz/archive/">Archive</a></li>';
    if (module_exists('webteam_statistics')) {
      $vars['linked_logo_img'] .=  '<li class="last leaf"><a class="archive" href="'.base_path().'siteinfo">Site Information</a></li>';
    }
    $vars['linked_logo_img'] .= '</ul>';
     
    switch ($vars['elements']['#region']) {
      case 'branding':
        $vars['linked_logo_img'] = $vars['logo'] ? l($vars['logo_img'], 'http://infoweb.ird.govt.nz/iris/', array('attributes' => array('rel' => 'home', 'title' => check_plain($vars['site_name'])), 'html' => TRUE)) : '';
      break;
    }
    
    
  }
}


/**
 * Get rid of Home in breadcrumb trail.
*/
function mothershipD7_breadcrumb($variables) {  
  $breadcrumb = $variables['breadcrumb'];
  
  if (!empty($breadcrumb)) {  
    
    // Provide a navigational heading to give context for breadcrumb links to
    // screen-reader users. Make the heading invisible with .element-invisible.
    $output = '<span class="element-invisible">' . t('You are here') . '</span>';
    $title = drupal_get_title();
    
    //array_shift($breadcrumb); // Removes the Home item
    $breadcrumb[] = $title;
    $output .= '<div class="breadcrumb">' . implode(' Â» ', $breadcrumb) . '</div>';
    
    return $output;
  }
} 

function mothershipD7_css_alter(&$css) {
  if (isset($css['misc/ui/jquery.ui.theme.css'])) {
    $css['misc/ui/jquery.ui.theme.css']['data'] = drupal_get_path('theme', 'mothershipD7') . '/css/jquery-ui.css';
  }
  if (isset($css['misc/ui/jquery.ui.tabs.css'])) {
    unset($css['misc/ui/jquery.ui.tabs.css']);
  }
}

/* function mothershipD7_page_alter(&$page) {
   
   $regions = system_region_list($GLOBALS['theme'], REGIONS_ALL);
   foreach ($regions as $region => $name) {      
      if (in_array($region, array('footer_first'))) {
        $page['footer_first'] = array(
                  '#region' => 'footer_first',
                  '#weight' => '-10',
                  '#theme_wrappers' => array('region'),
        );
      }
   }  
   
   //dsm($page);
   $page['content']['content']['footer_first']= array();
  
}
*/


/* sitemap remove unwanted title 
function mothershipD7_site_map_box($variables) {
  $title = $variables['title'];
  $content = $variables['content'];
  $attributes = $variables['attributes'];

  $output = '';
  if (!empty($title) || !empty($content)) {
    $output .= '<div' . drupal_attributes($attributes) . '>';
    if (!empty($title)) {
      //$output .= '<h2 class="title">' . $title . '</h2>';
    }
    if (!empty($content)) {
      $output .= '<div class="content">' . $content . '</div>';
    }
    $output .= '</div>';
  }

  return $output;
}
*/




/*
 *  Login form customisation - HR
 */
function mothershipD7_form_user_login_alter(&$form, &$form_state) {
  $form['name']['#description'] = t('');
  $form['pass']['#description'] = t('');
}


/* Override theme for shout box */
function mothershipD7_shoutbox_post($variables) {
  $shout = $variables['shout'];
  $links = $variables['links'];

  global $user;
  $img_links = '';
  // Gather moderation links
  if ($links) {
    foreach ($links as $link) {
      $link_html = '<img src="' . $link['img'] . '"  width="' . $link['img_width'] . '" height="' . $link['img_height'] . '" alt="' . $link['title'] . '" class="shoutbox-imglink"/>';
      $link_url = 'shout/' . $shout->shout_id . '/' . $link['action'];
      $img_links = l($link_html, $link_url, array('html' => TRUE, 'query' => array('destination' => drupal_get_path_alias($_GET['q'])))) . $img_links;
    }
  }
  
  // Generate user name with link
  $author = user_load($shout->uid);
  
  if(isset($author->signature)){
    $shout->nick =  $author->signature;
  }
  
  $user_name = shoutbox_get_user_link($shout);

  // Generate title attribute
  $title = t('Posted !date at !time by !name', array('!date' => format_date($shout->created, 'custom', 'm/d/y'), '!time' => format_date($shout->created, 'custom', 'h:ia'), '!name' => $shout->nick));

  // Add to the shout classes
  $shout_classes = array();
  $shout_classes[] = 'shoutbox-msg';

  // Check for moderation
  $approval_message = NULL;
  if ($shout->moderate == 1) {
    $shout_classes[] = 'shoutbox-unpublished';
    $approval_message = '&nbsp;(' . t('This shout is waiting for approval by a moderator.') . ')';
  }

  // Check for specific user class
  $user_classes = array();
  $user_classes[] = 'shoutbox-user-name';
  if ($shout->uid == $user->uid) {
    $user_classes[] = 'shoutbox-current-user-name';
  }
  else if ($shout->uid == 0) {
    $user_classes[] = 'shoutbox-anonymous-user';
  }

  // Build the post
  $post = '';
  $post .= '<div class="' . implode(' ', $shout_classes) . '" title="' . $title . '">';
  $post .= '<div class="shoutbox-admin-links">' . $img_links . '</div>';
  $post .= '<span class="' . implode(' ', $user_classes) . '">' . $user_name . '</span>:&nbsp;';
  //$post .= '<span class="' . implode(' ', $user_classes) . '">' . $author->signature . '</span>:&nbsp;';
  
  $post .= '<span class="shoutbox-shout">' . $shout->shout . $approval_message . '</span>';
  $post .= '<span class="shoutbox-msg-time">';
  $format = variable_get('shoutbox_time_format', 'ago');
  
  switch ($format) {
    case 'ago':
      $post .=  t('!interval ago', array('!interval' => format_interval(REQUEST_TIME - $shout->created)));
      break;
    case 'small':
    case 'medium':
    case 'large':
      $post .= format_date($shout->created, $format);
      break;
  }
  $post .= '</span>';
  $post .= '</div>' . "\n";

  return $post;
}

/* Apply title attribute to all site, Add title and alt tooltip - webstandard */
function mothershipD7_preprocess_link(&$vars) {
  // If there is already a title set, and it's not empty, we don't need to continue.
  if (isset($vars['options']['attributes']['title']) && !empty($vars['options']['attributes']['title'])) {
    return;
  }

  // Otherwise we use the link text as the title.
  $vars['options']['attributes']['title'] = strip_tags($vars['text']);
}