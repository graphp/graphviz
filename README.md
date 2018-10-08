# graphp/graphviz [![Build Status](https://travis-ci.org/graphp/graphviz.svg?branch=master)](https://travis-ci.org/graphp/graphviz)

GraphViz graph drawing for mathematical graph/network

The library supports visualizing graph images, including them into webpages,
opening up images from within CLI applications and exporting them
as PNG, JPEG or SVG file formats (among many others).
Because [graph drawing](http://en.wikipedia.org/wiki/Graph_drawing) is a complex area on its own,
the actual layouting of the graph is left up to the excelent [GraphViz](http://www.graphviz.org/)
"Graph Visualization Software" and we merely provide some convenient APIs to interface with GraphViz.

> Note: This project is in beta stage! Feel free to report any issues you encounter.

**Table of contents**

* [Quickstart examples](#quickstart-examples)
* [Attributes](#attributes)
  * [Graph attributes](#graph-attributes)
  * [Vertex attributes](#vertex-attributes)
  * [Edge attributes](#edge-attributes)
* [Install](#install)
* [Tests](#tests)
* [License](#license)

## Quickstart examples

Once [installed](#install), let's build and display a sample graph:

````php
$graph = new Fhaculty\Graph\Graph();

$blue = $graph->createVertex('blue');
$blue->setAttribute('graphviz.color', 'blue');

$red = $graph->createVertex('red');
$red->setAttribute('graphviz.color', 'red');

$edge = $blue->createEdgeTo($red);
$edge->setAttribute('graphviz.color', 'grey');

$graphviz = new Graphp\GraphViz\GraphViz();
$graphviz->display($graph);
````

The above code will open your default image viewer with the following image:

![red-blue](examples/01-simple.png)

See also the [examples](examples/).

## Attributes

GraphViz supports a number of attributes on the graph instance itself, each
vertex instance (GraphViz calls these "nodes") and edge instance. Any of these
GraphViz attributes are supported by this library and have to be assigned using
GraPHP attributes as documented below.

For the full list of all GraphViz attributes, please refer to the
[GraphViz documentation](https://graphviz.gitlab.io/_pages/doc/info/attrs.html).

### Graph attributes

GraphViz supports a number of attributes on the graph instance itself. Any of
these GraphViz attributes are supported by this library and have to be assigned
on the graph instance with the `graphviz.graph.` prefix like this:

```php
$graph = new Fhaculty\Graph\Graph();
$graph->setAttribute('graphviz.graph.bgcolor', 'transparent');
```

> Note how this uses the `graphviz.graph.` prefix and not just `graphviz.`. This
  is done for consistency reasons with respect to default vertex and edge
  attributes as documented below.

### Vertex attributes

GraphViz supports a number of attributes on each vertex instance (GraphViz calls
these "node" attributes). Any of these GraphViz attributes are supported by this
library and have to be assigned on the respective vertex instance with the
`graphviz.` prefix like this:

```php
$graph = new Fhaculty\Graph\Graph();

$blue = $graph->createVertex('blue');
$blue->setAttribute('graphviz.color', 'blue');
```

Additionally, GraphViz also supports default attributes for all vertices. Any of
these GraphViz attributes are supported by this library and have to be assigned
on the graph instance with the `graphviz.node.` prefix like this:

```php
$graph = new Fhaculty\Graph\Graph();
$graph->setAttribute('graphviz.node.color', 'grey');

$grey = $graph->createVertex('grey');
```

These default attributes can be overriden on each vertex instance by explicitly
assigning the same attribute on the respective vertex instance like this:

```php
$graph = new Fhaculty\Graph\Graph();
$graph->setAttribute('graphviz.node.color', 'grey');

$blue = $graph->createVertex('blue');
$blue->setAttribute('graphviz.color', 'blue');
```

> Note how this uses the `graphviz.node.` prefix and not `graphviz.vertex.`. This
  is done for consistency reasons with respect to how GraphViz assigns these
  default attributes in its DOT output.

### Edge attributes

GraphViz supports a number of attributes on each edge instance. Any of these
GraphViz attributes are supported by this library and have to be assigned on the
respective edge instance with the `graphviz.` prefix like this:

```php
$graph = new Fhaculty\Graph\Graph();

$a = $graph->createVertex('a');
$b = $graph->createVertex('b');

$blue = $a->createEdgeTo($b);
$blue->setAttribute('graphviz.color', 'blue');
```

Additionally, GraphViz also supports default attributes for all edges. Any of
these GraphViz attributes are supported by this library and have to be assigned
on the graph instance with the `graphviz.edge.` prefix like this:

```php
$graph = new Fhaculty\Graph\Graph();
$graph->setAttribute('graphviz.edge.color', 'grey');

$a = $graph->createVertex('a');
$b = $graph->createVertex('b');

$grey = $a->createEdgeTo($b);
```

These default attributes can be overriden on each edge instance by explicitly
assigning the same attribute on the respective edge instance like this:

```php
$graph = new Fhaculty\Graph\Graph();
$graph->setAttribute('graphviz.edge.color', 'grey');

$a = $graph->createVertex('a');
$b = $graph->createVertex('b');

$blue = $a->createEdgeTo($b);
$blue->setAttribute('graphviz.color', 'blue');
```

## Install

The recommended way to install this library is [through composer](http://getcomposer.org). [New to composer?](http://getcomposer.org/doc/00-intro.md)

```JSON
{
    "require": {
        "graphp/graphviz": "~0.2.0"
    }
}
```

This project aims to run on any platform and thus does not require any PHP
extensions and supports running on legacy PHP 5.3 through current PHP 7+ and
HHVM.
It's *highly recommended to use PHP 7+* for this project.

In order to be able to use the [graph drawing feature](#graph-drawing) you'll have to
install GraphViz (`dot` executable). Users of Debian/Ubuntu-based distributions may simply
invoke `sudo apt-get install graphviz`, Windows users have to
[download GraphViZ for Windows](http://www.graphviz.org/Download_windows.php) and remaining
users should install from [GraphViz homepage](http://www.graphviz.org/Download.php).

## Tests

To run the test suite, you first need to clone this repo and then install all
dependencies [through Composer](https://getcomposer.org):

```bash
$ composer install
```

To run the test suite, go to the project root and run:

```bash
$ php vendor/bin/phpunit
```

## License

Released under the terms of the permissive [MIT license](http://opensource.org/licenses/MIT).
