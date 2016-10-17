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
namespace bitExpert\Etcetera\Reader\File\Excel;

use bitExpert\Etcetera\Reader\File\AbstractFileReader;
use bitExpert\Etcetera\Reader\File\Excel\HeaderDetector\FirstRowHeaderDetector;
use bitExpert\Etcetera\Reader\File\Excel\HeaderDetector\HeaderDetector;

/**
 * Generic implementation of an {@link \bitExpert\Etcetera\Reader\IReader} for
 * Microsoft Excel files.
 */
abstract class AbstractExcelReader extends AbstractFileReader
{
    /**
     * @var int
     */
    protected $sheet;
    /**
     * @var HeaderDetector
     */
    protected $headerDetector;

    /**
     * AbstractExcelReader constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->sheet = 0;
        $this->headerDetector = new FirstRowHeaderDetector();
    }

    public function setSheet(int $sheet)
    {
        $this->sheet = $sheet;
    }

    public function setHeaderDetector(HeaderDetector $headerDetector)
    {
        $this->headerDetector = $headerDetector;
    }
}
