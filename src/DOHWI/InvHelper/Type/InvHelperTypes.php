<?php

declare(strict_types=1);

namespace DOHWI\InvHelper\Type;

use pocketmine\utils\EnumTrait;
use pocketmine\network\mcpe\protocol\types\inventory\WindowTypes;

/**
 * @method static TYPE_DOUBLE_CHEST
 * @method static TYPE_HOPPER
 * @method static TYPE_DISPENSER
 * @method static TYPE_SINGLE_CHEST
 */
final class InvHelperTypes
{
    use EnumTrait
    {
        __construct as Enum__construct;
    }

    public function __construct(string $enumName,private ? int $typeId = null)
    {
        $this->Enum__construct($enumName);
    }

    public function getTypeId() : ? int
    {
        return $this->typeId;
    }

    protected static function setup() : void
    {
        self::registerAll(
            new self('TYPE_DOUBLE_CHEST'),
            new self('TYPE_HOPPER',WindowTypes::HOPPER),
            new self('TYPE_DISPENSER',WindowTypes::DISPENSER),
            new self('TYPE_SINGLE_CHEST',WindowTypes::CONTAINER)
        );
    }
}