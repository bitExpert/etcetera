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

use Akeneo\Component\SpreadsheetParser\SpreadsheetParser;

/**
 * Generic implementation of an {@link \bitExpert\Etcetera\Reader\IReader} for "modern"
 * Microsoft Excel files.
 */
class Excel2007Reader extends AbstractExcelReader
{
    /**
     * {@inheritDoc}
     */
    public function read()
    {
        $this->checkFile($this->filename);

        try {
            $this->readSheet($this->filename, $this->sheet, $this->offset);
        } catch (\Exception $e) {
            throw new \RuntimeException(\sprintf('File %s not readable: %s', $this->filename, $e->getMessage()));
        }
    }

    /**
     * reads the given sheet
     * @param $file
     * @param $sheet
     * @param $offset
     */
    protected function readSheet($file, $sheet, $offset = 0)
    {
        $this->logInfo(sprintf('Try to read sheet "%s" from file "%s"', $sheet, $file));
        $workbook = SpreadsheetParser::open($file);

        $worksheets = $workbook->getWorksheets();
        if (!array_key_exists($sheet, $worksheets)) {
            $this->logWarning(sprintf(
                'Tried to read non existing sheet with index "%s" from file "%s"',
                $sheet,
                $file
            ));
            return;
        }

        $properties = [];

        $rowIterator = $workbook->createRowIterator($sheet);

        foreach ($rowIterator as $rowIndex => $values) {
            if ($this->headerDetector->isHeader($rowIndex, $values)) {
                $properties = $this->describeProperties($values);
                continue;
            }

            if ($rowIndex < $offset) {
                continue;
            }

            if (!count($properties)) {
                continue;
            }

            $this->processValues($properties, $values);
        }

        if (empty($properties)) {
            $this->logWarning('No properties found to process');
        }
    }
}
