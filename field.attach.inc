Drupal-codesnippet
==================

  /* patch https://www.drupal.org/files/issues/field_attach_debug.patch */
  $invalid_instances = array();
  foreach ($instances as $key => $instance) {
    if (!isset($instance['field_name']) || !isset($instance['field_id'])) {
      $invalid_instances[] = $instance;
      unset($instances[$key]);
    }
  }
  if ($invalid_instances) {
    $callstack = debug_backtrace();
    debug($callstack[1]['function'], 'caller');
    debug($entity_type, 'entity type');
    debug($bundle, 'bundle');
    debug($options, 'options');
    foreach ($invalid_instances as $instance) {
      debug($instance, 'instance');
    }
  }
