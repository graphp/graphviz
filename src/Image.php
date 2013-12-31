<?php

namespace Graphp\GraphViz;

use Graphp\GraphViz\GraphViz;
use Fhaculty\Graph\Graph;
use Fhaculty\Graph\Exporter\ExporterInterface;

class Image implements ExporterInterface
{
    private $format = 'png';

    public function getOutput(Graph $graph)
    {
        $graphviz = new GraphViz();
        $graphviz->setFormat($this->format);

        return $graphviz->createImageData($graph);
    }

    /**
     * set the image output format to use
     *
     * @param string $type png, svg
     * @return self $this (chainable)
     * @see GraphViz::setFormat()
     */
    public function setFormat($type)
    {
        $this->format = $type;
        return $this;
    }
}
