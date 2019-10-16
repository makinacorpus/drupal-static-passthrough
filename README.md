# Static Passthrough

Drupal 8 Module - Passthrough static files to Drupal 8

## Use cases with defaut config

### Passthrough statics template

1. Create a `static` folder in `DRUPAL_ROOT` directory.
2. Put a test.html file in it which contains `<h1>Hello word</h1>`
3. Then visit `example.com/dev/static/test.html`

The HTML is printed in the Content region in Front.

### Passthrough documentation

1. Create a `docs` folder in `DRUPAL_ROOT` directory.
2. Put a index.html file in it which contains `<h1>Hello word</h1>`
3. Then visit `example.com/admin/doc`

You're redirected to `example.com/admin/doc/index.html` and the file outside Drupal theme
