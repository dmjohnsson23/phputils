# phputils
A collection of scripts, classes, utility functions, and what not that I am working on for use in php projects.


## db
An abstract class and several triats designed to aid in the creation of so called 'sql-speaking objects'. This does not aim to be an ORM or to make it so you never have to write SQL, but it is designed to take away a lot of the boilerplate code and what not.
Usage: 
```
require_once 'db/object.php';
require_once 'db/ordered.php';



class Article extends DBObject{
  use Ordered;

  protected static $_table = 'articles';
  protected static $_columns = ['urlname', 'displayname', 'order_index'];
  protected public static $_pdo;
  
}

Article::$pdo = $pdo;
```

## inputs
Simplifies the creation of forms, and allows for more complicated and specialized imput types to be contructed, including a very simple wysiwyg editor. Contains some javascript and relies on jquery.

## wysiwyg
A work in progress; a bigger more fully featured wysiwyg editor. Not going to lie it works like crap right now and is pretty finnicky. Definately not the next google docs...
