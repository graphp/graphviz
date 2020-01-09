<?php

namespace Graphp\GraphViz;

use Graphp\Graph\Graph;

class Dot
{
    private $graphviz;

    public function __construct(GraphViz $graphviz = null)
    {
        if ($graphviz === null) {
            $graphviz = new GraphViz();
        }

        $this->graphviz = $graphviz;
    }

    public function getOutput(Graph $graph)
    {
        return $this->graphviz->createScript($graph);
    }
}
