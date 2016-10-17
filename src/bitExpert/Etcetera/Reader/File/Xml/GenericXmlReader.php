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

    /**
     * AXmlReader constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->rootNodeXPath = '//';
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
     * @inheritdoc
     */
    public function read()
    {
        $xml = simplexml_load_file($this->filename);
        $this->registerXPathNamespaces($xml);

        if (0 !== $this->offset) {
            throw new \InvalidArgumentException('Offset is not yet supported');
        }

        $nodes = $xml->xpath($this->rootNodeXPath);
        foreach ($nodes as $node) {
            $index = 0;
            $properties = [];
            $values = [];

            $node = json_decode(json_encode($node), true);

            foreach ($node as $property => $value) {
                //skip empty array values since they represent empty properties
                if (is_array($value) && !count($value)) {
                    continue;
                }

                $properties[$index] = $property;
                $values[$index] = $value;
                $index++;
            }


            $properties = $this->describeProperties($properties);
            $this->processValues($properties, $values);
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
