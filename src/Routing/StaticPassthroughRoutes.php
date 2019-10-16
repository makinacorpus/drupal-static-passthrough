<?php

namespace Drupal\static_passthrough\Routing;

use Symfony\Component\Routing\Route;

/**
 * Defines dynamic routes.
 */
class StaticPassthroughRoutes {

  /**
   * {@inheritdoc}
   */
  public function routes() {
    $routes = [];

    $config = \Drupal::config('static_passthrough.settings');

    foreach ($config->get('static_passthrough.directories') as $name => $directory) {
      $route_name = 'static_passthrough.' . $name;

      $routes[$route_name] = new Route(
        $directory['root_path'] . '/{relative_path}',
        [
          '_controller' => '\Drupal\static_passthrough\Controller\StaticPassthroughController::content',
          'name' => $name,
          'relative_path'=> '',
        ],
        [
          '_permission' => $directory['permission'],
          'relative_path' => '^[^\?]*$',
        ]
      );
    }

    return $routes;
  }
}
