<?php
declare(strict_types=1);

/*
 * This file is part of the TargetedMarketingModul package.
 *
 * (c) bitExpert AG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bitExpert\Etcetera\Reader\File;

use bitExpert\Etcetera\Reader\AbstractReader;
use bitExpert\Etcetera\Reader\Meta;

/**
 * Class AFileReader
 *
 * @package bitExpert\Etcetera\Reader;
 */
abstract class AbstractFileReader extends AbstractReader implements FileReader
{
    /**
     * @var string
     */
    protected $filename;
    /**
     * @var int
     */
    protected $offset;

    /**
     * @inheritDoc
     */
    public function setFilename(string $filename)
    {
        $this->filename = $filename;
    }

    /**
     * @inheritDoc
     */
    public function setOffset(int $offset)
    {
        $this->offset = $offset;
    }

    /**
     * @inheritdoc
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * {@inheritDoc}
     */
    abstract public function read();

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

    /**
     * Checks if file of given filename is readable
     * @param string $filename
     * @throws \RuntimeException
     */
    protected function checkFile($filename)
    {
        if (!\is_readable($filename)) {
            throw new \RuntimeException(\sprintf('Could not read file "%s"', $filename));
        }
    }
}
