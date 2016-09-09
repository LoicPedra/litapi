# LitApi

LitApi is a lite PHP framework to write simple and powerful APIs. 

## Installation

Clone this repository and copy src folder in your project! 

## Usage

Create an index.php file with the following contents:

```php
<?php

require 'src/Autoloader.php';



$app = new LitApi\App();

$app->get('/hello/:name', function ($args) {
    echo "Hello, " . $args['name'];
});

$app->run();
```

You may quickly test this using the built-in PHP server:
```bash
$ php -S localhost:8000
```

Going to http://localhost:8000/hello/john will now display "Hello, john".

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

Thus project is licensed under the MIT license. See [License File](LICENSE.md) for more information.
