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
namespace bitExpert\Etcetera\Extractor\Extract;

class Extract
{
    /**
     * @var EntityExtract[]
     */
    private $entityExtracts;
    /**
     * @var RelationExtract[]
     */
    private $relationExtracts;

    /**
     * Extract constructor.
     * @param array $entityExtracts
     * @param array $relationExtracts
     */
    public function __construct(array $entityExtracts, array $relationExtracts)
    {
        $this->entityExtracts = $entityExtracts;
        $this->relationExtracts = $relationExtracts;
    }

    /**
     * @return EntityExtract[]
     */
    public function getEntityExtracts(): array
    {
        return $this->entityExtracts;
    }

    /**
     * @return RelationExtract[]
     */
    public function getRelationExtracts(): array
    {
        return $this->relationExtracts;
    }
}
