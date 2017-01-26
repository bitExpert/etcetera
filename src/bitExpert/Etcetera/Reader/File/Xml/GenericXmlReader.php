<?php
declare(strict_types = 1);

/*
 * This file is part of the TargetedMarketingModul package.
 *
 * (c) bitExpert AG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bitExpert\Etcetera\Reader\File\Xml;

use bitExpert\Etcetera\Reader\File\AbstractFileReader;
use bitExpert\Etcetera\Reader\Meta;

/**
 * Generic implementation of an {@link \bitExpert\Etcetera\Reader\IReader} for
 * XML files
 */
class GenericXmlReader extends AbstractFileReader
{
    /**
     * @var string
     */
    protected $rootNodeXPath;

    /*
     * @var int;
     */
    protected $maxDepth;
    
    /**
     * AXmlReader constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->rootNodeXPath = '//';
        $this->maxDepth = 0;
    }

    /**
     * Sets the XPath for the node which shall act as root for reading
     * @param string $rootNodeXPath
     */
    public function setRootNodeXPath(string $rootNodeXPath)
    {
        $this->rootNodeXPath = $rootNodeXPath;
    }

    /**
     * Sets the maximum depth to look for sub tags in file
     * @param int $maxDepth
     */
    public function setMaxDepth(int $maxDepth)
    {
        $this->maxDepth = $maxDepth;
    }

    /**
     * @inheritdoc
     */
    public function read()
    {
        $xml = simplexml_load_file($this->filename);
        $this->registerXPathNamespaces($xml);

        if (0 !== (int)$this->offset) {
            throw new \InvalidArgumentException('Offset is not yet supported');
        }

        $nodes = $xml->xpath($this->rootNodeXPath);
        foreach ($nodes as $node) {
            $index = 0;
            $properties = [];
            $values = [];

            $node = json_decode(json_encode($node), true);

            $this->processSubTags($values, $properties, $index, $node);

            $properties = $this->describeProperties($properties);

            $this->processValues($properties, $values);
        }
    }

    /**
     * Iterates nodes and fills value and property arrays with found values and matching paths
     *
     * @param array  $values
     * @param array  $properties
     * @param int    $index
     * @param array  $node
     * @param int    $depth
     * @param string $parent
     */
    protected function processSubTags(
        array &$values,
        array &$properties,
        int &$index,
        array $node,
        int $depth = 0,
        string $parent = ''
    ) {
        foreach ($node as $property => $value) {
            //skip empty array values since they represent empty properties
            // also skip if configured max depth is reached
            if (is_array($value) && !count($value) || $depth > $this->maxDepth) {
                continue;
            }
            //if value is an array the actual tag contains sub tags
            //else value is the content of the actual tag
            if (!is_array($value)) {
                $properties[$index] = $parent . $property;
                $values[$index] = $value;
                $index++;
            } else {
                $NewParent = $parent . $property . '/';
                $this->processSubTags($values, $properties, $index, $value, ++$depth, $NewParent);
                $depth--;
            }
        }
    }

    /**
     * Registers found doc namespaces to xpath for being able to access
     * elements via xpath
     *
     * @param \SimpleXMLElement $doc
     */
    protected function registerXPathNamespaces(\SimpleXMLElement $doc)
    {
        foreach ($doc->getDocNamespaces() as $strPrefix => $strNamespace) {
            if (strlen($strPrefix) == 0) {
                $strPrefix = $strNamespace;
            }

            $doc->registerXPathNamespace($strPrefix, $strNamespace);
        }
    }

    /**
     * @return Meta
     */
    public function getMeta()
    {
        return (new Meta())->withSourceType(pathinfo($this->filename, PATHINFO_EXTENSION))
            ->withSourceName(pathinfo($this->filename, PATHINFO_FILENAME))
            ->withSourceDate(filemtime($this->filename))
            ->withProcessStartTime(time());
    }
}
