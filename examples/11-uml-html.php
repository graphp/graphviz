<?php

use Graphp\GraphViz\GraphViz;

require __DIR__ . '/../vendor/autoload.php';

$graph = new Graphp\Graph\Graph();

$a = $graph->createVertex()->setAttribute('id', 'Entity');
$a->setAttribute('graphviz.shape', 'none');
$a->setAttribute('graphviz.label_html', '
<table cellspacing="0" border="0" cellborder="1">
    <tr><td bgcolor="#eeeeee"><b>\N</b></td></tr>
    <tr><td></td></tr>
    <tr><td>+ touch()</td></tr>
</table>');

$b = $graph->createVertex()->setAttribute('id', 'Block');
$graph->createEdgeDirected($b, $a);
$b->setAttribute('graphviz.shape', 'none');
$b->setAttribute('graphviz.label_html', '
<table cellspacing="0" border="0" cellborder="1">
    <tr><td bgcolor="#eeeeee"><b>\N</b></td></tr>
    <tr><td>- size:int</td></tr>
    <tr><td>+ touch()</td></tr>
</table>');

$graphviz = new GraphViz();
echo $graphviz->createScript($graph);
$graphviz->display($graph);
