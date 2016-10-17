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

interface HeaderDetector
{
    /**
     * Returns whether given row is a header row or not
     *
     * @param int $rowIndex
     * @param array $values
     * @return bool
     */
    public function isHeader(int $rowIndex, array $values) : bool;
}
