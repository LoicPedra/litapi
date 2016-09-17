## DOCUMENTATION

### Before development

To load all classes that you need, add the LitApi's autolader with this line:

```php
require 'src/LitApi/Autoloader.php';
```

So, your project structure will be :

* `Src` folder which contains LitApi sources
* Your index file
* Your .htaccess (optional)

#### APP

To use routing, instantiate LitApi application with:

```php
$app = new LitApi\App();
```

Now, you can use App's functions defined as:

##### `GET` function

This function checks GET request and calls "callable" if the url matches.

```php
function get(string $path, function or string $callable)
```

* `$path` path which must match to url \*
* `$callable` if callable is a function, the function will be called if the url matches with the url. If it is a string, it call a controller and its function \*\*

\* _You can use url parameter with `:` symbol_

\*\* _The string must be formatted like `ControllerName#functionName`_

###### Example

```php
$app->get('/give/:number/:name', function(\Router\RouterRequest $request)
{
    $args = $request->getArgs();

    echo "Give $args[0] cookies to $args[1]";

});
```
OR
```php
$app->get('/home', "HomeController#show");
```

##### `POST` function

This function checks POST request and calls "callable" if the url matches.

```php
function post(string $path, function or string $callable)
```

* `$path` path which must match to url \*
* `$callable` if callable is a function, the function will be called if the url matches with the url. If it is a string, it call a controller and its function \*\*

\* _You can use url parameter with `:` symbol_

\*\* _The string must be formatted like `ControllerName#functionName`_

###### Example

```php
$app->post('/buy/:number', function(\Router\RouterRequest $request)
{
    $args = $request->getArgs();

    echo "Buy $args[0] cookies";

});
```
OR
```php
$app->post('/message', "MessageController#post");
```

##### `REQUEST` function

```php
function request(string $method, string $path, function or string $callable)
```