<?php

namespace Drupal\static_passthrough\PathProcessor;

use Drupal\Core\PathProcessor\InboundPathProcessorInterface;
use Symfony\Component\HttpFoundation\Request;

class StaticPassthroughPathProcessor implements InboundPathProcessorInterface {

  public function processInbound($path, Request $request) {
    $config = \Drupal::config('static_passthrough.settings');

    foreach ($config->get('static_passthrough.directories') as $directory) {
      $base_path = '/' . $directory['root_path'] . '/';

      if (\strpos($path, $base_path) === 0) {
        $escaped_root_path = \str_replace('/','\/', $base_path);
        $relative_path = \preg_replace('|^' . $escaped_root_path . '|', '', $path);
        $relative_path = \str_replace('/',':', $relative_path);
        return \sprintf("%s%s", $base_path, $relative_path);
      }
    }

    return $path;
  }
}