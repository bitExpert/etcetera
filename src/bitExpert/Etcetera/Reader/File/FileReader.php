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

use bitExpert\Etcetera\Reader\Reader;

/**
 * Interface IReader
 */
interface FileReader extends Reader
{
    /**
     * @param $filename
     * @param string
     */
    public function setFilename(string $filename);

    /**
     * @param $offset
     * @param int
     */
    public function setOffset(int $offset);
}
