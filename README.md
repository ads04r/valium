Valium
======

"would you like something to help you REST?"

Very often I end up writing some command line PHP code and then later want
to allow web access. The normal tool I use is PHP-Fatfree[1], an excellent
PHP framework that supports templates, routing, PHP sessions and a whole
heap of other cool stuff. It has two drawbacks... firstly, it's a complete
waste of the framework's capabilities to just bolt it on afterwards, and
secondly, it is licensed under GPL, which is no good if you want to release
your code under something else.

Valium is the (or rather, a) solution. It's CC-0 (effectively public domain)
and all the code is included within a single class, so including it should
not affect your project in any negative way. However, with a simple
index.php script you can build a web interface to your existing code with
just a few lines of PHP.

Use
===

To use Valium in your project:

> include_once("[PATH]/valium.php");
>
> $v = new Valium();

You will also need to install htaccess-sample in the appropriate directory
inside htdocs, named .htaccess.


Method: *route*
---------------

> void route ( string $regex, function $callback )

The *route* function acts in a similar way to FatFree's routing engine. The
browser URI is compared to the regular expression $regex, and, if it matches,
the $callback function is called. The function should be of the following
format:

> function $f ( $match_array, $get, $post )

The $match_array id an array as created by the *preg_match* function[2]. See
the documentation for that function for more information. $get contains the
arguments sent as part of the URI, and $post contains the POST data, if any,
as an array. The function should return a value.

Method: *run*
----------------

> array run ( void )

The *run* function calls the necessary functions based on browser input and
returns it ready for formatting, for example, as JSON:

> print(json_encode($f->run(), true));

Example *index.php* file
========================

> $v = new Valium();
> $v->throw_404_on_error = true;
> 
> $v->route("|/hello.world/([a-z0-9]+)$|", function($m, $get, $post)
> {
> 	$ret = array();
> 	$ret['message'] = $m[1];
> 	return(ret);
> });
> 
> header("Content-type: application/json");
> print(json_encode($v->run(), true));
> exit();

Call with http://[server]/[path]/hello.world/[something]

References
==========

1. https://github.com/bcosca/fatfree
2. http://php.net/manual/en/function.preg-match.php
