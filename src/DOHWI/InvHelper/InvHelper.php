<?php

declare(strict_types=1);

namespace DOHWI\InvHelper;

use DOHWI\InvHelper\Inventory\InventoryHelper;
use DOHWI\InvHelper\Inventory\InvHelperTransaction;

use pocketmine\Server;
use pocketmine\plugin\Plugin;
use pocketmine\event\EventPriority;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\inventory\transaction\action\SlotChangeAction;

final class InvHelper
{
    public static ? Plugin $plugin = null;

    public static function register(Plugin $plugin) : void
    {
        if(self::$plugin) return;
        Server::getInstance()->getPluginManager()->registerEvent(InventoryTransactionEvent::class,static function(InventoryTransactionEvent $event) : void
        {
            $transaction = $event->getTransaction();
            $player = $transaction->getSource();
            foreach($transaction->getActions() as $action)
            {
                if(!$action instanceof SlotChangeAction) continue;
                $inventory = $action->getInventory();
                if(!$inventory instanceof InventoryHelper) continue;
                $transaction = new InvHelperTransaction($player,$action->getSlot(),$action->getSourceItem(),$action->getTargetItem());
                $inventory->transactionHandler($transaction);
                if($transaction->isCancelled()) $event->cancel();
            }
        },EventPriority::NORMAL,$plugin);
        self::$plugin = $plugin;
    }
}