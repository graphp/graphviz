<?php

require __DIR__ . '/../vendor/autoload.php';

$graph = new Graphp\Graph\Graph();

$blue = $graph->createVertex();
$blue->setAttribute('id', 'blue');
$blue->setAttribute('graphviz.color', 'blue');

$red = $graph->createVertex();
$red->setAttribute('id', 'red');
$red->setAttribute('graphviz.color', 'red');

$edge = $graph->createEdgeDirected($blue, $red);
$edge = $blue->createEdgeTo($red);
$edge->setAttribute('graphviz.color', 'grey');

$graphviz = new Graphp\GraphViz\GraphViz();
$graphviz->display($graph);
