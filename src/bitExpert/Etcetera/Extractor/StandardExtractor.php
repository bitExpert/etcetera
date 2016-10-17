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

use bitExpert\Etcetera\Common\Logging\LoggerTrait;
use bitExpert\Etcetera\Extractor\Exception\ExtractExclusionException;
use bitExpert\Etcetera\Extractor\Extract\Extract;
use bitExpert\Etcetera\Extractor\Source\Descriptor\ValueDescriptor;

/**
 * Class StandardExtractor
 * Default implementation for Extractor
 */
class StandardExtractor implements Extractor
{
    use LoggerTrait;
    /**
     * @var EntityExtractor[]
     */
    private $entityExtractors;
    /**
     * @var RelationExtractor[]
     */
    private $relationExtractors;

    /**
     * @param EntityExtractor[] $entityExtractors
     * @param RelationExtractor[] $relationExtractors
     */
    public function __construct(array $entityExtractors, array $relationExtractors)
    {
        $this->entityExtractors = $entityExtractors;
        $this->relationExtractors = $relationExtractors;
    }

    /**
     * Extracts data
     *
     * @param ValueDescriptor[] $values
     * @return Extract
     */
    public function extract(array $values): Extract
    {
        $entityExtracts = [];
        foreach ($this->entityExtractors as $name => $entityExtractor) {
            try {
                $entityExtract = $entityExtractor->extract($values);
                if (!is_null($entityExtract)) {
                    $entityExtracts[$name] = $entityExtract;
                }
            } catch (ExtractExclusionException $e) {
                $this->logWarning($e->getMessage());
            }
        }

        $relationExtracts = [];
        /** @var RelationExtractor $relationExtractor */
        foreach ($this->relationExtractors as $name => $relationExtractor) {
            try {
                $relationExtract = $relationExtractor->extract($entityExtracts, $values);
                if (!is_null($relationExtract)) {
                    $relationExtracts[] = $relationExtract;
                }
            } catch (ExtractExclusionException $e) {
                $this->logWarning($e->getMessage());
            }
        }

        return new Extract($entityExtracts, $relationExtracts);
    }
}
