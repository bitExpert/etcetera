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

/**
 * Generic implementation of an {@link \bitExpert\Etcetera\Reader\Reader} for
 * legacy Microsoft Excel files.
 */
class LegacyExcelReader extends AbstractExcelReader
{
    /**
     * {@inheritDoc}
     */
    public function read()
    {
        $inputFileName = $this->filename;

        try {
            $inputFileType = \PHPExcel_IOFactory::identify($inputFileName);
            $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch (\Exception $e) {
            die(sprintf(
                'Error loading file "%s": %s',
                pathinfo($inputFileName, PATHINFO_BASENAME),
                $e->getMessage()
            ));
        }

        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        if (0 !== $this->offset) {
            //@todo implement
            throw new \InvalidArgumentException('Offset is not yet supported');
        }


        $properties = [];


        for ($rowIndex = 1; $rowIndex <= $highestRow; $rowIndex++) {
            $values = $sheet->rangeToArray(
                'A' . $rowIndex . ':' . $highestColumn . $rowIndex,
                null,
                null,
                false
            );
            $values = $values[0];

            if ($this->headerDetector->isHeader($rowIndex, $values)) {
                $properties = $this->describeProperties($values);
                continue;
            }

            if ($rowIndex < $this->offset) {
                continue;
            }

            if (!count($properties)) {
                continue;
            }

            $this->processValues($properties, $values);
        }
    }

    /**
     * @param string $filename
     * @return \PHPExcel_Reader_IReader
     */
    protected function getPhpExcelReader(string $filename)
    {
        $this->setPhpExcelConfigurations();

        $reader = \PHPExcel_IOFactory::createReaderForFile($filename);

        $instanceName = \get_class($reader);
        switch ($instanceName) {
            case 'PHPExcel_Reader_Excel2007':
            case 'PHPExcel_Reader_Excel5':
            case 'PHPExcel_Reader_OOCalc':
                /* @var $reader \PHPExcel_Reader_Excel2007 */
                $reader->setReadDataOnly(true);
                break;
        }

        $phpExcel = $reader->load($filename);

        return $phpExcel;
    }

    /**
     * Set PHP Excel configurations
     */
    protected function setPhpExcelConfigurations()
    {
        // set caching configuration
        $cacheMethod = \PHPExcel_CachedObjectStorageFactory::cache_in_memory;
        \PHPExcel_Settings::setCacheStorageMethod(
            $cacheMethod
        );
    }
}
