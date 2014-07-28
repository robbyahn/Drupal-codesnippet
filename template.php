<?php

/* Apply title attribute to all site, Add title and alt tooltip - webstandard */
function mothershipD7_preprocess_link(&$vars) {
  // If there is already a title set, and it's not empty, we don't need to continue.
  if (isset($vars['options']['attributes']['title']) && !empty($vars['options']['attributes']['title'])) {
    return;
  }

  // Otherwise we use the link text as the title.
  $vars['options']['attributes']['title'] = strip_tags($vars['text']);
}