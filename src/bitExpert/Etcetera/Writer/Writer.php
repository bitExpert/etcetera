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
namespace bitExpert\Etcetera\Writer;

use bitExpert\Etcetera\Extractor\Extract\Extract;
use bitExpert\Etcetera\Reader\Meta;

/**
 * Interface IWriter
 */
interface Writer
{
    /**
     * Gets called before the first write call is made
     * @return void
     */
    public function setUp();

    /**
     * Writes the extracted data to the defined target
     *
     * @param Extract $extract
     * @param Meta $meta
     */
    public function write(Extract $extract, Meta $meta);

    /**
     * Gets called after the last write call was made
     *
     * @return mixed
     */
    public function tearDown();
}
