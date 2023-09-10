<?php
namespace superarmor\middle1;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\item\VanillaItems;
use pocketmine\Server;
use pocketmine\utils\Config;
use jojoe77777\FormAPI\SimpleForm;
use superarmor\middle1\Data\ {
    Enchantments, Items
};

class Main extends PluginBase implements Listener
{
    private Config $config;

    public function onEnable() : void
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        //Создание конфига
        if (!is_dir($this->getDataFolder())) {
            mkdir($this->getDataFolder());
        }
        $this->saveResource("config.yml");
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool
    {
        if ($command->getName() == "armor") {
            if (!$sender instanceof Player) {
                $this->getLogger()->info("Only player");
                return true;
            }

            if (!$sender->isSurvival()) {
                $sender->sendMessage("§cIn survival only!");
                return true;
            }

            $form = new SimpleForm(
                function (Player $sender, ? int $result = null) {
                    if ($result === null) {
                        return true;
                    }
                    $inventory = $sender->getInventory();
                    switch ($result) {
                        case 0:
                            $item_in_hand_name = strtoupper($inventory->getItemInHand()->getVanillaName()); //переменная для проверки id предмета в руке игрока
                            $item_in_hand_name = str_replace(" ", "_", $item_in_hand_name); // замена всех пробелов в название на _
                            $item_count = $inventory->getItemInHand()->getCount(); //переменная для счёта кол-во предметов в руке игрока
                            $EnchantmentsClass = new Enchantments($this->config); //объявление Enchantments класса
                            $ItemsClass = new Items($this->config); //объявления Items класса
                            $price = $this->config->getNested("full-armor.cost");
                            $item_for_buy = $this->config->getNested("buy-armor.name-item-for-buy");
                            if ($item_in_hand_name != $item_for_buy || $item_count < $price) {
                                $sender->sendMessage(
                                    "Take in hand  " . $price . " " . $item_for_buy);
                                return true;
                            }
                            if(empty(VanillaItems::$item_for_buy())){
                                Server::getInstance()->getLogger()->warning("The " . $item_for_buy ." is not VanillaItems");
                                return false;
                            }
                            $inventory->removeItem(VanillaItems::$item_for_buy()->setCount($price));
                            $position = $sender->getPosition();
                            //Все предметы
//                          $boots = $ItemsClass->getName("boots"); // Ботинки
//                          $chest = $ItemsClass->getName("chest"); // Кирасса
//                          $helmet = $ItemsClass->getName("helmet"); // Шлем
//                          $leggins = $ItemsClass->getName("leggins"); // Штаны
                            // Все чары
                            $boots = $EnchantmentsClass->getEnchantments("boots");
                            $chest = $EnchantmentsClass->getEnchantments("chest");
                            $helmet = $EnchantmentsClass->getEnchantments("helmet");
                            $leggins = $EnchantmentsClass->getEnchantments("leggins");
                            // Имена предметов
                            $boots = $ItemsClass->setName("boots", $boots);
                            $chest = $ItemsClass->setName("chest", $chest);
                            $helmet = $ItemsClass->setName("helmet", $helmet);
                            $leggins = $ItemsClass->setName("leggins", $leggins);

                            if (!$inventory->canAddItem($boots)) {
                                $position->getWorld()->dropItem($position, $boots);
                            } else {
                                $inventory->addItem($boots);
                            }

                            if (!$inventory->canAddItem($chest)) {
                                $position->getWorld()->dropItem($position, $chest);
                            } else {
                                $inventory->addItem($chest);
                            }

                            if (!$inventory->canAddItem($helmet)) {
                                $position->getWorld()->dropItem($position, $helmet);
                            } else {
                                $inventory->addItem($helmet);
                            }

                            if (!$inventory->canAddItem($leggins)) {
                                $position->getWorld()->dropItem($position, $leggins);
                            } else {
                                $inventory->addItem($leggins);
                            }
                            break;

                        case 1:
                            $sender->sendMessage("After purchase you will be given armor " . $this->config->getNested("leggins.enchants.level") . " protection\nThe cost of this armor " . $this->config->getNested("full-armor.cost") . " diamonds");
                            break;

                        case 2:
                            break;
                    }
                    return false;
                }
            );
            $form->setTitle("§n§dPurchase of super armor");
            $form->addButton("§8To buy super armor", 0, "textures/items/chainmail_chestplate.png");
            $form->addButton("§7FAQ", 0, "textures/items/book_normal.png");
            $form->addButton("§o§cExit");
            $form->sendToPlayer($sender);
            return true;
        }
        return true;
    }
}
