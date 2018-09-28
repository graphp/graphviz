# graphp/graphviz [![Build Status](https://travis-ci.org/graphp/graphviz.svg?branch=master)](https://travis-ci.org/graphp/graphviz)

GraphViz graph drawing for mathematical graph/network

The library supports visualizing graph images, including them into webpages,
opening up images from within CLI applications and exporting them
as PNG, JPEG or SVG file formats (among many others).
Because [graph drawing](http://en.wikipedia.org/wiki/Graph_drawing) is a complex area on its own,
the actual layouting of the graph is left up to the excelent [GraphViz](http://www.graphviz.org/)
"Graph Visualization Software" and we merely provide some convenient APIs to interface with GraphViz.

> Note: This project is in beta stage! Feel free to report any issues you encounter.

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

![red-blue](https://cloud.githubusercontent.com/assets/776829/6545433/636e8534-c587-11e4-94f4-4d540b4564ed.png)

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
