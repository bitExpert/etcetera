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

class RelationExtract extends EntityExtract
{
    /**
     * @var EntityExtract
     */
    private $from;
    /**
     * @var EntityExtract
     */
    private $to;

    /**
     * RelationExtract constructor.
     * @param string $type
     * @param PropertyExtract[] $propertyExtracts
     * @param EntityExtract|null $from
     * @param EntityExtract|null $to
     */
    public function __construct(
        string $type,
        array $propertyExtracts,
        EntityExtract $from = null,
        EntityExtract $to = null
    ) {
        parent::__construct($type, $propertyExtracts);
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * Returns the source EntityExtract for the relation, null if it does not exist
     * @return EntityExtract|null
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Returns the target EntityExtract for the relation, null if it does not exist
     * @return EntityExtract|null
     */
    public function getTo()
    {
        return $this->to;
    }
}
