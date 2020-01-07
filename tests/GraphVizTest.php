<?php

use Graphp\Graph\Graph;
use Graphp\GraphViz\GraphViz;
use PHPUnit\Framework\TestCase;

class GraphVizTest extends TestCase
{
    private $graphViz;

    public function setUp()
    {
        $this->graphViz = new GraphViz();
    }

    public function testGraphEmpty()
    {
        $graph = new Graph();

        $expected = <<<VIZ
graph {
}

VIZ;

        $this->assertEquals($expected, $this->graphViz->createScript($graph));
    }

    public function testGraphWithName()
    {
        $graph = new Graph();
        $graph->setAttribute('graphviz.name', 'G');

                $expected = <<<VIZ
graph "G" {
}

VIZ;

        $this->assertEquals($expected, $this->graphViz->createScript($graph));
    }

    public function testGraphWithNameWithSpaces()
    {
        $graph = new Graph();
        $graph->setAttribute('graphviz.name', 'My Graph Name');

                $expected = <<<VIZ
graph "My Graph Name" {
}

VIZ;

        $this->assertEquals($expected, $this->graphViz->createScript($graph));
    }

    public function testGraphIsolatedVertices()
    {
        $graph = new Graph();
        $graph->createVertex()->setAttribute('id', 'a');
        $graph->createVertex()->setAttribute('id', 'b');

        $expected = <<<VIZ
graph {
  "a"
  "b"
}

VIZ;

        $this->assertEquals($expected, $this->graphViz->createScript($graph));
    }

    public function testGraphIsolatedVerticesWillAssignNumericIdsWhenNotExplicitlyGiven()
    {
        $graph = new Graph();
        $graph->createVertex();
        $graph->createVertex();

        $expected = <<<VIZ
graph {
  1
  2
}

VIZ;

        $this->assertEquals($expected, $this->graphViz->createScript($graph));
    }

    public function testGraphIsolatedVerticesWithGroupsWillBeAddedToClusters()
    {
        $graph = new Graph();
        $graph->createVertex()->setAttribute('id', 'a')->setAttribute('group', 0);
        $graph->createVertex()->setAttribute('id', 'b')->setAttribute('group', 'foo bar')->setAttribute('graphviz.label', 'second');

        $expected = <<<VIZ
graph {
  subgraph cluster_0 {
    label = 0
    "a"
  }
  subgraph cluster_1 {
    label = "foo bar"
    "b" [label="second"]
  }
}

VIZ;

        $this->assertEquals($expected, $this->graphViz->createScript($graph));
    }

    public function testGraphDefaultAttributes()
    {
        $graph = new Graph();
        $graph->setAttribute('graphviz.graph.bgcolor', 'transparent');
        $graph->setAttribute('graphviz.node.color', 'blue');
        $graph->setAttribute('graphviz.edge.color', 'grey');

        $expected = <<<VIZ
graph {
  graph [bgcolor="transparent"]
  node [color="blue"]
  edge [color="grey"]
}

VIZ;

        $this->assertEquals($expected, $this->graphViz->createScript($graph));
    }

    public function testUnknownGraphAttributesWillBeDiscarded()
    {
        $graph = new Graph();
        $graph->setAttribute('graphviz.vertex.color', 'blue');
        $graph->setAttribute('graphviz.unknown.color', 'red');

        $expected = <<<VIZ
graph {
}

VIZ;

        $this->assertEquals($expected, $this->graphViz->createScript($graph));
    }

    public function testEscaping()
    {
        $graph = new Graph();
        $graph->createVertex()->setAttribute('id', 'a');
        $graph->createVertex()->setAttribute('id', 'b¹²³ is; ok\\ay, "right"?');
        $graph->createVertex()->setAttribute('id', 3);
        $graph->createVertex()->setAttribute('id', 4)->setAttribute('graphviz.label', 'normal');
        $graph->createVertex()->setAttribute('id', 5)->setAttribute('graphviz.label_html', '<b>html-like</b>');
        $graph->createVertex()->setAttribute('id', 6)->setAttribute('graphviz.label_html', 'hello<br/>wörld');
        $graph->createVertex()->setAttribute('id', 7)->setAttribute('graphviz.label_record', '<port>first|{second1|second2}');
        $graph->createVertex()->setAttribute('id', 8)->setAttribute('graphviz.label_record', '"\N"');

        $expected = <<<VIZ
graph {
  "a"
  "b¹²³ is; ok\\\\ay, &quot;right&quot;?"
  3
  4 [label="normal"]
  5 [label=<<b>html-like</b>>]
  6 [label=<hello<br/>wörld>]
  7 [label="<port>first|{second1|second2}"]
  8 [label="\\"\\N\\""]
}

VIZ;

        $this->assertEquals($expected, $this->graphViz->createScript($graph));
    }

    public function testGraphWithSimpleEdgeUsesGraphWithSimpleEdgeDefinition()
    {
        // a -- b
        $graph = new Graph();
        $graph->createEdgeUndirected($graph->createVertex()->setAttribute('id', 'a'), $graph->createVertex()->setAttribute('id', 'b'));

        $expected = <<<VIZ
graph {
  "a" -- "b"
}

VIZ;

        $this->assertEquals($expected, $this->graphViz->createScript($graph));
    }

    public function testGraphWithLoopUsesGraphWithSimpleLoopDefinition()
    {
        // a -- b -\
        //      |  |
        //      \--/
        $graph = new Graph();
        $a = $graph->createVertex()->setAttribute('id', 'a');
        $b = $graph->createVertex()->setAttribute('id', 'b');
        $graph->createEdgeUndirected($a, $b);
        $graph->createEdgeUndirected($b, $b);

        $expected = <<<VIZ
graph {
  "a" -- "b"
  "b" -- "b"
}

VIZ;

        $this->assertEquals($expected, $this->graphViz->createScript($graph));
    }

    public function testGraphDirectedUsesDigraph()
    {
        // a -> b
        $graph = new Graph();
        $graph->createEdgeDirected($graph->createVertex()->setAttribute('id', 'a'), $graph->createVertex()->setAttribute('id', 'b'));

        $expected = <<<VIZ
digraph {
  "a" -> "b"
}

VIZ;

        $this->assertEquals($expected, $this->graphViz->createScript($graph));
    }

    public function testGraphDirectedWithLoopUsesDigraphWithSimpleLoopDefinition()
    {
        // a -> b -\
        //      ^  |
        //      \--/
        $graph = new Graph();
        $a = $graph->createVertex()->setAttribute('id', 'a');
        $b = $graph->createVertex()->setAttribute('id', 'b');
        $graph->createEdgeDirected($a, $b);
        $graph->createEdgeDirected($b, $b);

        $expected = <<<VIZ
digraph {
  "a" -> "b"
  "b" -> "b"
}

VIZ;

        $this->assertEquals($expected, $this->graphViz->createScript($graph));
    }

    public function testGraphMixedUsesDigraphWithExplicitDirectionNoneForUndirectedEdges()
    {
        // a -> b -- c
        $graph = new Graph();
        $a = $graph->createVertex()->setAttribute('id', 'a');
        $b = $graph->createVertex()->setAttribute('id', 'b');
        $c = $graph->createVertex()->setAttribute('id', 'c');
        $graph->createEdgeDirected($a, $b);
        $graph->createEdgeUndirected($c, $b);

        $expected = <<<VIZ
digraph {
  "a" -> "b"
  "c" -> "b" [dir="none"]
}

VIZ;

        $this->assertEquals($expected, $this->graphViz->createScript($graph));
    }

    public function testGraphMixedWithDirectedLoopUsesDigraphWithoutDirectionForDirectedLoop()
    {
        // a -- b -\
        //      ^  |
        //      \--/
        $graph = new Graph();
        $a = $graph->createVertex()->setAttribute('id', 'a');
        $b = $graph->createVertex()->setAttribute('id', 'b');
        $graph->createEdgeUndirected($a, $b);
        $graph->createEdgeDirected($b, $b);

        $expected = <<<VIZ
digraph {
  "a" -> "b" [dir="none"]
  "b" -> "b"
}

VIZ;

        $this->assertEquals($expected, $this->graphViz->createScript($graph));
    }

    public function testGraphUndirectedWithIsolatedVerticesFirst()
    {
        // a -- b -- c   d
        $graph = new Graph();
        $a = $graph->createVertex()->setAttribute('id', 'a');
        $b = $graph->createVertex()->setAttribute('id', 'b');
        $c = $graph->createVertex()->setAttribute('id', 'c');
        $graph->createVertex()->setAttribute('id', 'd');
        $graph->createEdgeUndirected($a, $b);
        $graph->createEdgeUndirected($b, $c);

        $expected = <<<VIZ
graph {
  "d"
  "a" -- "b"
  "b" -- "c"
}

VIZ;

        $this->assertEquals($expected, $this->graphViz->createScript($graph));
    }

    public function testVertexLabels()
    {
        $graph = new Graph();
        $graph->createVertex()->setAttribute('id', 'a')->setAttribute('balance', 1);
        $graph->createVertex()->setAttribute('id', 'b')->setAttribute('balance', 0);
        $graph->createVertex()->setAttribute('id', 'c')->setAttribute('balance', -1);
        $graph->createVertex()->setAttribute('id', 'd')->setAttribute('graphviz.label', 'test');
        $graph->createVertex()->setAttribute('id', 'e')->setAttribute('balance', 2)->setAttribute('graphviz.label', 'unnamed');

        $expected = <<<VIZ
graph {
  "a" [label="a (+1)"]
  "b" [label="b (0)"]
  "c" [label="c (-1)"]
  "d" [label="test"]
  "e" [label="unnamed (+2)"]
}

VIZ;

        $this->assertEquals($expected, $this->graphViz->createScript($graph));
    }

    public function testEdgeLayoutAtributes()
    {
        $graph = new Graph();
        $graph->createEdgeUndirected($graph->createVertex()->setAttribute('id', '1a'), $graph->createVertex()->setAttribute('id', '1b'));
        $graph->createEdgeUndirected($graph->createVertex()->setAttribute('id', '2a'), $graph->createVertex()->setAttribute('id', '2b'))->setAttribute('graphviz.numeric', 20);
        $graph->createEdgeUndirected($graph->createVertex()->setAttribute('id', '3a'), $graph->createVertex()->setAttribute('id', '3b'))->setAttribute('graphviz.textual', "forty");
        $graph->createEdgeUndirected($graph->createVertex()->setAttribute('id', '4a'), $graph->createVertex()->setAttribute('id', '4b'))->setAttribute('graphviz.1', 1)->setAttribute('graphviz.2', 2);
        $graph->createEdgeUndirected($graph->createVertex()->setAttribute('id', '5a'), $graph->createVertex()->setAttribute('id', '5b'))->setAttribute('graphviz.a', 'b')->setAttribute('graphviz.c', 'd');

        $expected = <<<VIZ
graph {
  "1a" -- "1b"
  "2a" -- "2b" [numeric=20]
  "3a" -- "3b" [textual="forty"]
  "4a" -- "4b" [1=1 2=2]
  "5a" -- "5b" [a="b" c="d"]
}

VIZ;

        $this->assertEquals($expected, $this->graphViz->createScript($graph));
    }

    public function testEdgeLabels()
    {
        $graph = new Graph();
        $graph->createEdgeUndirected($graph->createVertex()->setAttribute('id', '1a'), $graph->createVertex()->setAttribute('id', '1b'));
        $graph->createEdgeUndirected($graph->createVertex()->setAttribute('id', '2a'), $graph->createVertex()->setAttribute('id', '2b'))->setAttribute('weight', 20);
        $graph->createEdgeUndirected($graph->createVertex()->setAttribute('id', '3a'), $graph->createVertex()->setAttribute('id', '3b'))->setAttribute('capacity', 30);
        $graph->createEdgeUndirected($graph->createVertex()->setAttribute('id', '4a'), $graph->createVertex()->setAttribute('id', '4b'))->setAttribute('flow', 40);
        $graph->createEdgeUndirected($graph->createVertex()->setAttribute('id', '5a'), $graph->createVertex()->setAttribute('id', '5b'))->setAttribute('flow', 50)->setAttribute('capacity', 60);
        $graph->createEdgeUndirected($graph->createVertex()->setAttribute('id', '6a'), $graph->createVertex()->setAttribute('id', '6b'))->setAttribute('flow', 60)->setAttribute('capacity', 70)->setAttribute('weight', 80);
        $graph->createEdgeUndirected($graph->createVertex()->setAttribute('id', '7a'), $graph->createVertex()->setAttribute('id', '7b'))->setAttribute('flow', 70)->setAttribute('graphviz.label', 'prefixed');

        $expected = <<<VIZ
graph {
  "1a" -- "1b"
  "2a" -- "2b" [label=20]
  "3a" -- "3b" [label="0/30"]
  "4a" -- "4b" [label="40/∞"]
  "5a" -- "5b" [label="50/60"]
  "6a" -- "6b" [label="60/70/80"]
  "7a" -- "7b" [label="prefixed 70/∞"]
}

VIZ;

        $this->assertEquals($expected, $this->graphViz->createScript($graph));
    }

    public function testCreateImageSrcWillExportPngDefaultFormat()
    {
        $graph = new Graph();

        $src = $this->graphViz->createImageSrc($graph);

        $this->assertStringStartsWith('data:image/png;base64,', $src);
    }

    public function testCreateImageSrcAsSvgWithUtf8DefaultCharset()
    {
        $graph = new Graph();

        $this->graphViz->setFormat('svg');
        $src = $this->graphViz->createImageSrc($graph);

        $this->assertStringStartsWith('data:image/svg+xml;charset=UTF-8;base64,', $src);
    }

    public function testCreateImageSrcAsSvgzWithExplicitIsoCharsetLatin1()
    {
        $graph = new Graph();
        $graph->setAttribute('graphviz.graph.charset', 'iso-8859-1');

        $this->graphViz->setFormat('svgz');
        $src = $this->graphViz->createImageSrc($graph);

        $this->assertStringStartsWith('data:image/svg+xml;charset=iso-8859-1;base64,', $src);
    }
}
