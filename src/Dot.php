<?php

namespace Graphp\GraphViz;

use Graphp\GraphViz\GraphViz;
use Fhaculty\Graph\Graph;
use Fhaculty\Graph\Exporter\ExporterInterface;

class Dot implements ExporterInterface
{
    public function getOutput(Graph $graph)
    {
        $graphviz = new GraphViz($graph);
        return $graphviz->createScript();
    }
}
