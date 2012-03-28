# gravatar
================================

gravatar is a simple and straightforward php class that will generate a gravatar url. 


Usage is as simple as: 

```php
    $gravatar_url = gravatar::url('bob@bitcap.com');
```

Or, if you're feeling more demanding:

```php
    $email = filter_input(INPUT_GET,'e',FILTER_SANITIZE_EMAIL);
    
    $params = array(
        'secure'     => false,
        'default'    => 'monsterid',
        'rating'     => 'x',
        'size'       => 256
    );
    $url = gravatar::url($email,$params);
    echo '<h1>'. $url . '</h1><img src="'. $url .'" />';
    
```

For more information and commentary, see the [blog post](http://muddylemon.com/2011/12/php-gravatar-class/) that
describes its creation.