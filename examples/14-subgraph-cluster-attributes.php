<?php

use Graphp\GraphViz\GraphViz;

require __DIR__ . '/../vendor/autoload.php';

$graph = new Graphp\Graph\Graph();
$graph->setAttribute('graphviz.graph.rankdir', 'LR');
$graph->setAttribute('graphviz.subgraph.cluster_0.graph.bgcolor', 'lightblue');
$graph->setAttribute('graphviz.subgraph.cluster_1.node.fillcolor', 'lightgrey');
$graph->setAttribute('graphviz.subgraph.cluster_1.node.style', 'filled');

// create some cities
$rome = $graph->createVertex(array('id' => 'Rome'));
$rome->setAttribute('group', 'eu1');
$madrid = $graph->createVertex(array('id' => 'Madrid'));
$madrid->setAttribute('group', 'eu2');
$cologne = $graph->createVertex(array('id' => 'Cologne'));
$cologne->setAttribute('group', 'eu2');

// build some roads
$graph->createEdgeDirected($cologne, $madrid);
$graph->createEdgeDirected($madrid, $rome);
// create loop
$graph->createEdgeDirected($rome, $rome);

$graphviz = new GraphViz();
echo $graphviz->createScript($graph);
echo $graphviz->createImageFile($graph) . PHP_EOL;
