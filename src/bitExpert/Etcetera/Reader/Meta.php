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
namespace bitExpert\Etcetera\Reader;

/**
 * Class Meta
 * @package bitExpert\Etcetera\Reader
 */
class Meta
{
    /**
     * @var string
     */
    protected $sourceName;

    /**
     * @var string
     */
    protected $sourceDate;

    /**
     * @var string
     */
    protected $sourceType;

    /**
     * @var int
     */
    protected $processStartTime;

    /**
     * @return string
     */
    public function getSourceName()
    {
        return $this->sourceName;
    }

    /**
     * @param string $sourceName
     * @return Meta
     */
    public function withSourceName($sourceName)
    {
        $instance = clone $this;
        $instance->sourceName = $sourceName;
        return $instance;
    }

    /**
     * @return string
     */
    public function getSourceDate()
    {
        return $this->sourceDate;
    }

    /**
     * @param string $sourceDate
     * @return Meta
     */
    public function withSourceDate($sourceDate)
    {
        $instance = clone $this;
        $instance->sourceDate = $sourceDate;
        return $instance;
    }

    /**
     * @return string
     */
    public function getSourceType()
    {
        return $this->sourceType;
    }

    /**
     * @param string $sourceType
     * @return Meta
     */
    public function withSourceType($sourceType)
    {
        $instance = clone $this;
        $instance->sourceType = $sourceType;
        return $instance;
    }

    /**
     * @return int
     */
    public function getProcessStartTime()
    {
        return $this->processStartTime;
    }

    /**
     * @param int $processStartTime
     * @return Meta
     */
    public function withProcessStartTime($processStartTime)
    {
        $instance = clone $this;
        $instance->processStartTime = $processStartTime;
        return $instance;
    }

    public function toArray()
    {
        return [
            'sourceName' => $this->sourceName,
            'sourceDate' => $this->sourceDate,
            'sourceType' => $this->sourceType,
            'processStartTime' => $this->processStartTime
        ];
    }
}
