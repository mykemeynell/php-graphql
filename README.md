# PHP GraphQL Query Builder

The PHP GraphQL Query Builder is a powerful and flexible library that allows you to programmatically construct GraphQL queries and mutations in PHP. It provides an intuitive and expressive way to build complex GraphQL statements without the need for manual string concatenation.

## Installation

You can install the PHP GraphQL Query Builder via Composer. Run the following command in your project directory:

```bash
composer require mykemeynell/php-graphql
```

## Usage

To start building GraphQL queries and mutations, you need to create an instance of the `Query` or `Mutation` class, respectively. These classes extend the abstract `GraphBuilder` class, which provides the common functionality for constructing GraphQL statements.

### Basic Queries

Here's an example of how to build a simple GraphQL query:

```php
use GraphQL\Query;
use GraphQL\Field;

$query = new Query();
$query->addField('user', ['id' => 1], ['name', 'email']);

echo $query->build();
```

Output:
```graphql
query {
  user(id: 1) {
    name
    email
  }
}
```

In this example, we create a new `Query` instance and add a `user` field with an `id` argument and two sub-fields, `name` and `email`. The `build()` method is then called to generate the final GraphQL query string.

### Mutations

Building mutations is similar to building queries. Here's an example:

```php
use GraphQL\Mutation;
use GraphQL\Field;

$mutation = new Mutation();
$mutation->addField('createUser', ['name' => 'John Doe', 'email' => 'john@example.com']);

echo $mutation->build();
```

Output:
```graphql
mutation {
  createUser(name: "John Doe", email: "john@example.com")
}
```

In this case, we create a `Mutation` instance and add a `createUser` field with `name` and `email` arguments.

### Nested Fields

The query builder supports nested fields, allowing you to construct more complex queries. Here's an example:

```php
use GraphQL\Query;
use GraphQL\Field;

$query = new Query();
$query->addField('products', ['first' => 10], [
    new Field('edges', [], [
        new Field('node', [], ['id', 'title', 'price'])
    ])
]);

echo $query->build();
```

Output:
```graphql
query {
  products(first: 10) {
    edges {
      node {
        id
        title
        price
      }
    }
  }
}
```

In this example, we add a `products` field with a `first` argument and a nested `edges` field. The `edges` field contains a `node` field with `id`, `title`, and `price` sub-fields.

### Aliased Fields

You can use aliased fields to rename the fields in your query. Here's an example:

```php
use GraphQL\Query;

$query = new Query();
$query->addAliasedField('productName', 'name');
$query->addAliasedField('productPrice', 'price');

echo $query->build();
```

Output:
```graphql
query {
  productName: name
  productPrice: price
}
```

In this case, we use the `addAliasedField()` method to add aliased fields. The first argument is the alias, and the second argument is the actual field name.

### Variables

The query builder supports the use of variables in your queries and mutations. Here's an example:

```php
use GraphQL\Query;

$query = new Query();
$query->addField('user', ['id' => '$userId'], ['name', 'email']);

echo $query->build();
```

Output:
```graphql
query {
  user(id: $userId) {
    name
    email
  }
}
```

In this example, we use a variable `$userId` as the value for the `id` argument. The actual value for the variable can be provided when executing the query.

### Fragments

Fragments allow you to reuse common sets of fields across multiple queries or mutations. Here's an example:

```php
use GraphQL\Query;
use GraphQL\Fragment;

$userFields = new Fragment('userFields', 'User', ['name', 'email']);

$query = new Query();
$query->addField('user', ['id' => 1], [$userFields]);
$query->addField('users', ['first' => 10], [$userFields]);

echo $query->build();
```

Output:
```graphql
query {
  user(id: 1) {
    ...userFields
  }
  users(first: 10) {
    ...userFields
  }
}

fragment userFields on User {
  name
  email
}
```

In this example, we define a fragment named `userFields` on the `User` type with `name` and `email` fields. We then use this fragment in both the `user` and `users` fields of the query.

### Directives

Directives allow you to conditionally include or skip fields based on certain conditions. Here's an example:

```php
use GraphQL\Query;
use GraphQL\Directive;

$includeEmail = new Directive('include', ['if' => '$withEmail']);

$query = new Query();
$query->addField('user', ['id' => 1], [
    'name',
    new Field('email', [], [], [$includeEmail])
]);

echo $query->build();
```

Output:
```graphql
query {
  user(id: 1) {
    name
    email @include(if: $withEmail)
  }
}
```

In this example, we define an `include` directive with an `if` argument that depends on the `$withEmail` variable. We then apply this directive to the `email` field, conditionally including it based on the value of `$withEmail`.

## Conclusion

The PHP GraphQL Query Builder provides a powerful and expressive way to construct GraphQL queries and mutations in PHP. It supports a wide range of GraphQL features, including nested fields, aliased fields, variables, fragments, and directives.

By using this library, you can easily build complex GraphQL statements in a programmatic and maintainable way, without the need for manual string concatenation.

For more detailed information and advanced usage, please refer to the source code and API documentation.

The PHP GraphQL Query Builder library provides a powerful and flexible way to programmatically construct GraphQL queries and mutations in PHP. It supports a wide range of GraphQL features, including nested fields, aliased fields, variables, fragments, and directives.[1]
