<?php

use Graphp\GraphViz\GraphViz;

require __DIR__ . '/../vendor/autoload.php';

$graph = new Graphp\Graph\Graph();

$a = $graph->createVertex()->setAttribute('id', 'Entity');
$a->setAttribute('graphviz.shape', 'record');
$a->setAttribute('graphviz.label_record', '{\N||+ touch()}');

$b = $graph->createVertex()->setAttribute('id', 'Block');
$graph->createEdgeDirected($b, $a);
$b->setAttribute('graphviz.shape', 'record');
$b->setAttribute('graphviz.label_record', '{\N|- size:int|+ touch()}');

$graphviz = new GraphViz();
echo $graphviz->createScript($graph);
$graphviz->display($graph);
