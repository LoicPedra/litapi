# LitApi

LitApi is a lite PHP framework to write simple and powerful APIs. 

## Installation

Clone this repository and copy **src** folder in your project! 

## Usage

Create an **index.php** file with the following contents:

```php
<?php

require 'src/LitApi/Autoloader.php';

$app = new LitApi\App();

$app->get('/give/:number/:name', function(\Router\RouterRequest $request)
{
    $args = $request->getArgs();

    echo "Give $args[0] cookies to $args[1]";

})->with("number", "[0-9]{1,}")->with("name", "[a-zA-Z]{2,}");

$app->run();
```

### Apache

Create an **.htaccess** file with the following contents: 

```
RewriteEngine On

RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
```

### Nginx 

Set Nginx's configuration: 

```
# nginx configuration 
location / { 
if (!-e $request_filename){ 
rewrite ^(.*)$ /index.php?url=$1 break; 
} 
}
```

## Documentation

Please see [documentation](docs/README.md) for details.

## Contributing

Please see [contibuting](CONTRIBUTING.md) for details.

## License

This project is licensed under the MIT license. See [License File](LICENSE) for more information.
