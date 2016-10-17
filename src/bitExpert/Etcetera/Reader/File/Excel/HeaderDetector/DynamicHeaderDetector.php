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
namespace bitExpert\Etcetera\Reader\File\Excel\HeaderDetector;

final class DynamicHeaderDetector implements HeaderDetector
{
    /**
     * @var callable
     */
    private $detectorFunction;

    /**
     * DynamicHeaderDetector constructor.
     * @param callable $detectorFunction
     */
    public function __construct(callable $detectorFunction)
    {
        $this->detectorFunction = $detectorFunction;
    }

    /**
     * {@inheritDoc}
     */
    public function isHeader(int $rowIndex, array $values) : bool
    {
        $detector = $this->detectorFunction;
        return $detector($rowIndex, $values);
    }
}
