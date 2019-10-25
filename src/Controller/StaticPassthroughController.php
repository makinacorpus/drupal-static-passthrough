<?php
namespace Drupal\static_passthrough\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Provides route responses for the Static Passthrough module.
 */
class StaticPassthroughController extends ControllerBase {

  const MIME_TYPES = [
    // Web
    "css"=>"text/css",
    "js"=>"application/javascript",
    "html"=>"text/html",

    // Images
    "gif"=>"image/gif",
    "png"=>"image/png",
    "jpeg"=>"image/jpg",
    "jpg"=>"image/jpg",
    "svg" => "image/svg+xml",

    // Documents
    "pdf"=>"application/pdf",
    "docx"=>"application/msword",
    "doc"=>"application/msword",
    "xls"=>"application/vnd.ms-excel",
    "xlsx"=>"application/vnd.ms-excel",
    "ppt"=>"application/vnd.ms-powerpoint",
    "pptx"=>"application/vnd.ms-powerpoint",

    // Fonts
    "ttf" => "application/x-font-ttf",
    "otf" => "application/x-font-opentype",
    "woff" => "application/font-woff",
    "woff2" => "application/font-woff2",
    "eot" => "application/vnd.ms-fontobject",
    "sfnt" => "application/font-sfnt",
  ];

  /**
   * Returns a simple page.
   *
   * @return array|StreamedResponse
   *   A simple renderable array or a whole HttpResponse
   */
  public function content(Request $request, string $name, string $relative_path) {

    $config = \Drupal::config('static_passthrough.settings')->get('static_passthrough.directories');

    $directory_config = $config[$name];

    $path = \sprintf(
      "%s/%s/%s",
      DRUPAL_ROOT,
      $directory_config['root_dir'],
      \str_replace(':', '/', $relative_path)
    );

    $explode_path = \explode('/', $path);
    $last_slug = \array_pop($explode_path);
    if (\count(\explode('.', $last_slug)) <= 1 ) {
      return $this->redirectToHtml($request, $path);
    }

    $exploded_slug = \explode('.', $last_slug);
    $extension = \end($exploded_slug);
    if (!\in_array($extension, $directory_config['allowed_extension']) || !\file_exists($path)) {
      throw new NotFoundHttpException(\sprintf("Le fichier '%s' n'a pas pu être trouvé", $path));
    }

    // If the config is standalone, we return a streamResponse and the thml is returned alone.
    if ($directory_config['standalone']) {
      $resource = @\fopen($path, 'r');
      return new StreamedResponse(function () use ($resource) {
        \fpassthru($resource);
        exit();
      },
      200,
      [
        'Content-Transfer-Encoding', 'binary',
        'Content-Type' => $this->getMimeType($extension),
        'Content-Length' => \fstat($resource)['size'],
      ]
    );
    // Otherwise, we return a render array, like this the file will be include in the content region.
    } else {
      return [
        '#markup' => \file_get_contents($path),
      ];
    }
  }

  private function redirectToHtml(Request $request, string $path) {
    $path = \rtrim($path, '/');
    $original_uri = \rtrim($request->getRequestUri(), '/');

    $file_path = $path . '.html';
    $uri = $original_uri . '.html';
    // If file doesn't exist, we check if we have a index.html file instead
    if (!\file_exists($file_path)) {
      $file_path = $path . '/index.html';
      $uri = $original_uri . '/index.html';
      if (!\file_exists($file_path)) {
        throw new NotFoundHttpException(\sprintf("Le fichier '%s' n'a pas pu être trouvé", $path));
      }
    }

    return new RedirectResponse($uri);
  }

  private function getMimeType($extension) {
    if (\in_array($extension, \array_keys(self::MIME_TYPES))) {
      return self::MIME_TYPES[$extension];
    } else {
      throw new \InvalidArgumentException(\sprintf("L'extension %s n'est pas prise en charge pas le module statics_passthrough", $extension));
    }
  }
}
