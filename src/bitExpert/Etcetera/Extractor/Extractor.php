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
namespace bitExpert\Etcetera\Extractor;

use bitExpert\Etcetera\Extractor\Extract\Extract;

interface Extractor
{
    /**
     * Extracts data by given rules and returns an extract out of it
     *
     * @param array $values
     * @return Extract
     */
    public function extract(array $values) : Extract;
}
