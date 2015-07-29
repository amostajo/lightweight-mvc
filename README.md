# LIGHTWEIGHT MVC (For Wordpress plugins and themes)
--------------------------------

[![Latest Stable Version](https://poser.pugx.org/amostajo/lightweight-mvc/v/stable)](https://packagist.org/packages/amostajo/lightweight-mvc)
[![Total Downloads](https://poser.pugx.org/amostajo/lightweight-mvc/downloads)](https://packagist.org/packages/amostajo/lightweight-mvc)
[![License](https://poser.pugx.org/amostajo/lightweight-mvc/license)](https://packagist.org/packages/amostajo/lightweight-mvc)

**Lightweight MVC** is a small framework that adds *Models*, *Views* and *Controllers* to your custom **Wordpress** plugin or theme.

Lightweight MVC utilices existing Wordpress functionality preventing from overloading the already heavy loaded Wordpress core.

This framework was inspired by **Laravel** to add the MVC design pattern to Wordpress development in an efficient, elegant and optimized way.

- [Requirements](#requirements)
- [Installation](#configuration)
- [Configuration](#configuration)
- [Usage](#usage)
    - [Models](#models)
        - [Aliases](#aliases)
        - [Types](#types)
    - [Views](#views)
    - [Controllers](#controllers)
    - [Engine](#engine)
    - [Helpers](#helpers)
- [Coding Guidelines](#coding-guidelines)
- [License](#license)

## Requirements

* PHP >= 5.4.0

## Installation

Add

```json
"amostajo/lightweight-mvc": "1.0.*"
```

to your composer.json. Then run `composer install` or `composer update`.

**NOTE** If you are not using composer, you can download the ZIP but will need to include the files manually since not autoload will be generated.

## Configuration

Your project needs to store somewhere your `Models`, `Views` and `Controllers`, and **Lightweight MVC** must know where.

Create these as folders, like this:

```bash
[PROJECT ROOT]
 |---> [controllers]
 |---> [models]
 |---> [views]
```

Then in your `functions.php` or `plugins.php` file set these at the very beginnning, like:

```php
// Path to the controllers folder.
$engine = new Amostajo\LightweightMVC\Engine(

	// Path to the views folder.
	$views_path = 'path_to_views_folder',

	// Path to the controllers folder.
	$controllers_path = 'path_to_controllers_folder',

	// Namespace of your plugin or theme. For controller access
	$namespace = 'MyApp'
);
```

## Usage

### Models

In **Lightweight MVC**, a `Model` at the end is just a type of Wordpress post. Store your models at the `models` folder.

Here is model example:

```php
<?php

namespace MyApp\Models;

use Amostajo\LightweightMVC\Model as Model;
use Amostajo\LightweightMVC\Traits\FindTrait as FindTrait;

class Post extends Model
{
	use FindTrait;
}
```

With just that, you will be able to do this:
```php
$post = new MyApp\Models\Post();

$post->post_title = 'New post';
$post->save(); // New inserts.

// Find and get post model from id.
$post = MyApp\Models\Post::find( $post_id ); // Returns null if not found

// Delete post (move it to trash)
$post->delete();

// Update an attribute
$post->post_content = 'My content';
$post->save();

// Access to post meta
$meta = $post->meta;

// Cast to array
$array = $post->to_array();

// Cast to json
$array = $post->to_json();

// Cast to WP_Post
$array = $post->to_post();

// Get posts from parent.
$posts = MyApp\Models\Post::from( $parent_id );
```

#### Aliases

The model will let you add aliases to post attributes, meta values and event custom functions.

```php
<?php

namespace MyApp\Models;

use Amostajo\LightweightMVC\Model as Model;
use Amostajo\LightweightMVC\Traits\FindTrait as FindTrait;

class Post extends Model
{
	use FindTrait;

	protected $aliases = array(
		'title'		=> 'post_title',
		'content'	=> 'post_content',
	);
}

```

When adding aliases to properties you can do things like this:

```php
// Find and get post model from id.
$post = MyApp\Models\Post::find( $post_id );

echo $post->title; // Will echo post_title

$post->title = 'New title';
$post->save(); // Will save post_title
```

You can extend the attributes of a post with the meta table, the model does this seamlessly when adding aliases with `meta_` prefix:

```php
<?php

namespace MyApp\Models;

use Amostajo\LightweightMVC\Model as Model;
use Amostajo\LightweightMVC\Traits\FindTrait as FindTrait;

class Post extends Model
{
	use FindTrait;

	protected $aliases = array(
		'title'		=> 'post_title',
		'content'	=> 'post_content',
		'price'		=> 'meta_price', // Meta key "price"
	);
}
```

Usage:

```php
// Find and get post model from id.
$post = MyApp\Models\Post::find( $post_id );

echo $post->price; // Will echo meta value for meta key "price".

$post->price = 19.99;
$post->save(); // Will save meta value for meta key "price".
```

Sometimes you might need to add some logic to your attributes, you can accomplish this by adding an alias for a function using the `func_` prefix:

```php
<?php

namespace MyApp\Models;

use Amostajo\LightweightMVC\Model as Model;
use Amostajo\LightweightMVC\Traits\FindTrait as FindTrait;

class Post extends Model
{
	use FindTrait;

	protected $aliases = array(
		'title'		=> 'post_title',
		'content'	=> 'post_content',
		'price'		=> 'meta_price',
		'is_free'	=> 'func_is_free',
	);

	protected function is_free()
	{
		return $this->price <= 0;
	}
}
```

Usage:

```php
// Find and get post model from id.
$post = MyApp\Models\Post::find( $post_id );

echo $post->is_free; // Will return true or false depending expression.

// Function aliases can not save values though.
```

Aliases fields are included when casting the model to arrays, json or string.

#### Types

As mentioned before, a model is a post type. Once a post type has been registered by you plugin or theme, you can create a model for it.

In the following example a new post type `books` has been created to handle book records:

```php
<?php

namespace MyApp\Models;

use Amostajo\LightweightMVC\Model as Model;
use Amostajo\LightweightMVC\Traits\FindTrait as FindTrait;

class Book extends Model
{
	use FindTrait;

	/**
	 * Post type.
	 * @var string
	 */
	protected $type = 'books';
	/**
	 * Default post status.
	 * @var string
	 */
	protected $status = 'publish';

	protected $aliases = array(
		'title'			=> 'post_title',
		'description'	=> 'post_content',
		'year'			=> 'meta_year',
		'publisher'		=> 'meta_publisher',
	);
}
```

With just that, books can be used like this:

```php
$book = new MyApp\Models\Book();

$book->title = 'My diary';
$book->description = 'About me';
$book->year = 2015;
$book->publisher = 'www.evopiru.com';

$book->save(); // This will save a new post of type 'book' with status 'publish'. ('draft' is default)

// Find book
$book = new MyApp\Models\Book::find( $id );
```

### Views

This is the templating mini-engine of **Lightweight MVC**.

Views are PHP files whose content is HTML most of the time. PHP tags should only be used to echo values from variables passed by.

Here an example of a view that displays a book object from the example above:

```html
<div class="book book-<?php echo $book->ID ?>">

	<h1><?php echo $book->title ?></h1>

	<ul>
		<li>Year: <?php echo $book->year ?></li>
		<li>Publisher: <?php echo $book->publisher ?></li>
	</ul>

	<p><?php echo $book->description ?></p>

</div>
```

**NOTE:** There is no PHP logic in the view, just pure HTML, perfect for the designer to handle. Logic is placed in the controller.

You can place your views as please within the `views` folder. In example, let's assuming that the view file from above is
named `profile.php`, you can place it in a path like this:


```bash
[ROOT]
 |---> [views]
 |      |---> [books]
 |      |      |---> profile.php
```

The key locator for this view within the engine will be:

`books.profile`

**NOTE:** If this view is located inside a plugin, you can add the same hirachy in any theme to customize the view with the theme's styles. **Lightweight MVC** will give priority to the view located in the theme.

Something like this:

```bash
[THEME_ROOT]
 |---> [views]
 |      |---> [books]
 |      |      |---> profile.php
```

### Controllers

Controllers are used to create handle any business logic and algorithms.

In the following example, we will use a controller to display the book profile.

```php
<?php

namespace MyApp\Controllers;

use MyApp\Models\Book;
use Amostajo\LightweightMVC\View as View;
use Amostajo\LightweightMVC\Controller as Controller;

class BookController extends Controller
{
	/**
	 * Searches for book based on passed ID.
	 * Returns view books.profile.
	 */
	public function display_view( $id )
	{
		$book_model = Book::find( $id );

		// (1) Indicates which View to return
		// (2) We pass $book_model to the view as 'book'
		return $this->view->get( 'books.profile', array(
			'book' => $book_model,
		) );
	}

	/**
	 * Searches for book based on passed ID.
	 * Returns book as json.
	 */
	public function display_json( $id )
	{
		$book = Book::find( $id );

		// Returns book as json
		return $book->to_json();
	}
}
```

### Engine

Once you have everything in order, you can use the framework as following (using the same book example):

```php
// Calls:
// Controller: BookController
// Function: display_view
// Parameters passed by: $book_id
//
// call function will echo the result.
$engine->call( 'BookController@display_view', $book_id );

// Calls:
// Controller: BookController
// Function: display_json
// Parameters passed by: $book_id
//
// action function will return the result.
$json = $engine->action( 'BookController@display_json', $book_id );

// Echo a view directly
$engine->view->show( 'books.profile', array('book' => Book::find( $book_id )) );
```

### Helpers

This package comes with a request class helper, to retrieve values from GET, POST or WORDPRESS' query_vars:

```php
$name = Amostajo\LightweightMVC\Request::input( 'name', $default_value );
```

Third value will clear the source value, great to prevent loop wholes with NONCE:

```php
$nonce = Amostajo\LightweightMVC\Request::input( 'my_nonce', $default_value, $clear_source = true );
```

## Coding Guidelines

The coding is a mix between PSR-2 and Wordpress PHP guidelines.

## License

**Lightweight MVC** is free software distributed under the terms of the MIT license.