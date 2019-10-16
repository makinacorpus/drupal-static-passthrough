# Static Passthrough

Drupal 8 Module - Passthrough static files to Drupal 8

## Use cases with default config

You can see the explained default config in `config/install/static_passthrough.settings.yml`
These settings can be modified/overwritten as all Drupal 8 settings.

### Passthrough static templates

Configuration :

```yml
static_passthrough:
  directories:

    ...

    static:
      # Root path to get files
      root_path: 'dev/static'
      # Where to find files to passthrough (relative to DRUPAL_ROOT folder)
      root_dir: 'statics'
      # Who can access these files
      permission: 'access content'
      # Which kind of files are allowed
      allowed_extension: ['html', 'css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'ico', 'ttf', 'otf', 'woff', 'woff2', 'eot' ]
      # Do files have to be include in Drupal structure (false) or displayed alone (true)
      standalone: false

    ...

```

Try it :

1. Create a `static` folder in `DRUPAL_ROOT` directory.
2. Put a test.html file in it which contains `<h1>Hello word</h1>`
3. Then visit `example.com/dev/static/test.html`

The HTML is printed in the Content region in Front.

### Passthrough documentation

Configuration :

```yml
static_passthrough:
  directories:

    ...

    documentation:
      # Root path to get files
      root_path: 'admin/doc'
      # Where to find files to passthrough (relative to DRUPAL_ROOT folder)
      root_dir: 'docs'
      # Who can access these files
      permission: 'access administration pages'
      # Which kind of files are allowed
      allowed_extension: ['html', 'css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'ico', 'ttf', 'otf', 'woff', 'woff2', 'eot' ]
      # Do files have to be include in Drupal structure (false) or displayed alone (true)
      standalone: true

    ...

```

Try it :

1. Create a `docs` folder in `DRUPAL_ROOT` directory.
2. Put a index.html file in it which contains `<h1>Hello word</h1>`
3. Then visit `example.com/admin/doc`

You're redirected to the standalone `example.com/admin/doc/index.html` file.
