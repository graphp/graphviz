<?php

// $ php -S localhost:8080 examples/02-html.php

require __DIR__ . '/../vendor/autoload.php';

$graph = new Graphp\Graph\Graph();
$graph->setAttribute('graphviz.graph.rankdir', 'LR');

$hello = $graph->createVertex()->setAttribute('id', 'hello');
$world = $graph->createVertex()->setAttribute('id', 'wörld');
$graph->createEdgeDirected($hello, $world);

$graphviz = new Graphp\GraphViz\GraphViz();
$graphviz->setFormat('svg');

echo '<!DOCTYPE html>
<html>
<head>
<title>hello wörld</title>
<body>
' . $graphviz->createImageHtml($graph) . '
</body>
</html>
';
