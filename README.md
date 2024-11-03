# PHP GraphQL Wrapper

A simple wrapper for writing GraphQL statements in PHP.

## Installation

```composer require mykemeynell/php-graphql```

## Usage

```php
$query = (new Query(name: 'products'))
    ->addArguments([
        'first' => 10,
        'query' => 'product_type:snowboards',
    ])
    ->addSelect(['edges' => ['node' => ['title']]]);
```

```

```
