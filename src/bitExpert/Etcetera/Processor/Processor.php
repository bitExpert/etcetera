<?php
declare(strict_types = 1);

/*
 * This file is part of the Etcetera package.
 *
 * (c) bitExpert AG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bitExpert\Etcetera\Processor;

use bitExpert\Etcetera\Extractor\Extract\Extract;
use bitExpert\Etcetera\Common\Observer\Observer;
use bitExpert\Etcetera\Extractor\Extractor;
use bitExpert\Etcetera\Reader\Reader;
use bitExpert\Etcetera\Reader\Meta;
use bitExpert\Etcetera\Writer\Writer;
use bitExpert\Etcetera\Common\Logging\LoggerTrait;

/**
 * Class DefaultProcessor
 */
class Processor implements Observer
{
    use LoggerTrait;

    /**
     * @var Reader
     */
    protected $reader;

    /**
     * @var Writer
     */
    protected $writer;

    /**
     * @var Extractor
     */
    protected $extractor;

    /**
     * @var int
     */
    protected $processed;

    /**
     * ImportHandler constructor.
     * @param Reader $reader
     * @param Extractor $extractor
     * @param Writer $writer
     */
    public function __construct(Reader $reader, Extractor $extractor, Writer $writer)
    {
        $this->reader = $reader;
        $this->reader->registerObserver($this);
        $this->writer = $writer;

        $this->extractor = $extractor;
    }

    public function process()
    {
        $this->processed = 0;
        $this->writer->setUp();
        $this->reader->read();
        $this->writer->tearDown();
    }

    /**
     * Function which is called by the reader since we are registered
     * as observer
     */
    public function update()
    {
        $this->processValues($this->reader->getValues(), $this->reader->getMeta());
    }

    /**
     * @param array $values
     * @param Meta $meta
     * @throws \Exception
     */
    protected function processValues(array $values, Meta $meta)
    {
        $this->logDebug(sprintf('Processing extract #%s', ++$this->processed));
        $extract = $this->extractor->extract($values);
        $this->saveExtract($extract, $meta);
        $this->logDebug(sprintf('Processed extract #%s', $this->processed));
    }

    /**
     * @param Extract $extract
     * @param Meta $meta
     */
    protected function saveExtract(Extract $extract, Meta $meta)
    {
        $this->writer->write($extract, $meta);
    }
}
