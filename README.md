# Eloquent Wrapper of WordPress DB Models

This package was created in order to boost the productivity and to get the whole power of Laravel’s [Eloquent ORM](http://laravel.com/docs/5.8/eloquent).

The package supports  [Themosis Framework](https://framework.themosis.com/) and [Laravel](https://laravel.com/) which has a connection to a WordPress DB.

## Package Installation
```composer require rumur/wp-eloquent-models```

## Model Set
-   [Attachments](https://github.com/rumur/wp-eloquent-models#attachments)
-   [Comments](https://github.com/rumur/wp-eloquent-models#comments)
-   [Posts](https://github.com/rumur/wp-eloquent-models#posts)
	- [Creating your own](https://github.com/rumur/wp-eloquent-models#creates)
-   [Terms](https://github.com/rumur/wp-eloquent-models#terms)
-   [Users](https://github.com/rumur/wp-eloquent-models#users)
-   [Meta](https://github.com/rumur/wp-eloquent-models#meta)

### [Attachments](#attachments)
```php
<?php
use Rumur\WordPress\Eloquent\Model\Attachment;

// Getting an attachment 
$attachment = Attachment::find(2020);

// Available relationships
$attachment->meta;
$attachment->post;
$attachment->author;

// As a WordPress Entity
$attachment->toWordPressEntity(); // <- ?\WP_Post
```

### [Comments](#comments)
```php
<?php
use Rumur\WordPress\Eloquent\Model\Comment;

// Getting a comment
$comment = Comment::find(2020);

// Available relationships
$comment->meta;
$comment->post;

// As a WordPress Entity
$comment->toWordPressEntity(); // <- ?\WP_Comment
```

### [Posts](#posts)
```php
<?php
use Rumur\WordPress\Eloquent\Model\Post;

// Getting a post
$post = Post::find(2020);

// Available relationships
$post->meta;
$post->terms;
$post->author;
$post->comments;
$post->attachments;

// As a WordPress Entity
$post->toWordPressEntity(); // <- ?\WP_Post

// Taxonomy Scope
$posts = Post::limit(15)->taxonomy('post_tag')->get();

// Status Scope
$published = Post::limit(15)->status('publish')->get();

// Post Type Scope
$orders = Post::with(['author'])->limit(15)->type('order')->get();
```

### [Terms](#terms)
```php
<?php
use Rumur\WordPress\Eloquent\Model\Term;

// Getting a term with a specific id
$term = Term::with(['posts'])->find(2020);

// Available relationships
$term->meta;
$term->posts;

// As a WordPress Entity
$term->toWordPressEntity(); // <- ?\WP_Term

// Taxonomy Scope
$tags = Term::limit(15)->taxonomy('post_tag')->get();
```

### [Users](#users)
```php
<?php
use Rumur\WordPress\Eloquent\Model\User;

// Getting a user with a specific id
$user = User::find(2020);

// Available relationships
$user->meta;
$user->posts;
$user->comments;

// As a WordPress Entity
$user->toWordPressEntity(); // <- ?\WP_User
```

### [Meta](#meta)

The models `Attachment`, `Post`, `User`, `Comment`, `Term`, all use the `HasMeta` trait. Therefore their meta can easily be retrieved by the `getMeta`, deleted `deleteMeta` and set by the `setMeta` methods:

```php
<?php
use Rumur\WordPress\Eloquent\Model\{Attachment, Comment, Post, Term, User};

$post = Post::find(2020);

$post->setMeta('progress_status', 88);
$featured_img_id = $post->getMeta('_thumbnail_id');

// The same approach can be applied for all other models.

$user = User::find(2020);

$networks = $user->getMeta('networks');

$user->setMeta('networks',  [
	'twitter' => 'https://twitter.com/username',
	'facebook' => 'https://facebook.com/username', 
	'instagram' => 'https://instagram.com/username',
]);

$attachment = Attachment::find(2020);
$meta = $attachment->getMeta('any_attachment_meta_key');

$comment = Comment::find(2020);
$meta = $comment->getMeta('any_comment_meta_key');

$term = Term::find(2020);
$meta = $term->getMeta('any_term_meta_key');

// Delete meta.
Post::find(2020)->deleteMeta('any_meta_key');
Term::find(2020)->deleteMeta('any_meta_key');
User::find(2020)->deleteMeta('any_meta_key');
Comment::find(2020)->deleteMeta('any_meta_key');
Attachment::find(2020)->deleteMeta('any_meta_key');

```

### [Creating your own models](#creates)

If you want to create your own model, for instance, an `Product` it might be extended from a Post model, so you just need to apply a global scope with a  specific `post_type`  by adding a `Rumur\WordPress\Eloquent\Scope\HasPostTypeScope` trait to a model.

If your `post_type` is different from a class name of your model, you can explicitly tell which `post_type` you are going to use by adding a specific 
```php
<?php
namespace App\Model;

use Rumur\WordPress\Eloquent\Model\Post;
use Rumur\WordPress\Eloquent\Scope\HasPostTypeScope;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Product extends Post
{
    // Adds a global `post_tpe` scope
    use HasPostTypeScope;

    /**
     * The `post_type` for a model.
     *
     * @var string
     */
    protected static $postType = 'cpt_product';

    public function stores(): HasManyThrough
    {
        return $this->terms()->where('taxonomy', 'store');
    }
}
```

**Example without explicit pointing to the `post_type`**

```php
<?php
namespace App\Model;

use Rumur\WordPress\Eloquent\Model\Post;
use Rumur\WordPress\Eloquent\Scope\HasPostTypeScope;

class Page extends Post
{
    // Adds a global `post_tpe` scope
    use HasPostTypeScope;
}
```

## License
This package is licensed under the MIT License - see the [LICENSE.md](https://github.com/rumur/wp-eloquent-models/blob/master/LICENSE) file for details.