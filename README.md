# LitApi

LitApi is a lite PHP framework to write simple and powerful APIs. 

## Installation

Clone this repository and copy **src** folder in your project! 

## Usage

Create an **index.php** file with the following contents:

```php
<?php

require 'src/Autoloader.php';

$app = new LitApi\App();

// Example, see more in docs
$app->get('/hello/:name', function ($args) {
    echo "Hello, " . $args['name'];
});

$app->run();
```

### Apache

Create an **.htaccess** file with the following contents: 

```
RewriteEngine On

RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
```

## Contributing

Please see [documentation](docs/README.md) for details.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

Thus project is licensed under the MIT license. See [License File](LICENSE) for more information.
