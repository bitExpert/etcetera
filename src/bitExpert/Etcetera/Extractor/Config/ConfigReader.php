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
namespace bitExpert\Etcetera\Extractor\Config;

use PhpOption\Option;

/**
 * Interface ConfigReader
 */
interface ConfigReader
{
    /**
     * parse file by the given file path
     *
     * @param string $filename
     * @throws \InvalidArgumentException If file does not exist
     * @return Option
     */
    public function read(string $filename) : Option;
}
