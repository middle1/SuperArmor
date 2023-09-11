<?php
namespace superarmor\middle1;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\item\VanillaItems;
use pocketmine\utils\Config;
use pocketmine\math\Vector3;
use superarmor\middle1\libs\Form\SimpleForm;
use superarmor\middle1\Data\ {
    Enchantments, Items
};

class Main extends PluginBase implements Listener
{
    private $config;

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

    public function onCommand(CommandSender $p, Command $cmd, string $label, array $args) : bool
    {
        if ($cmd->getName() == "armor") {
            if (!$p instanceof Player) {
                $this->getLogger()->info("Only player");
                return true;
            }

            if (!$p->isSurvival()) {
                $p->sendMessage("§cIn survival only!");
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
                            if ($item_in_hand_name != $this->config->getNested("buy-armor.name-item-for-buy") || $item_count < $this->config->getNested("full-armor.cost")) {
                                $sender->sendMessage(
                                    "Take in hand  " . $this->config->getNested("full-armor.cost") . " diamonds");
                                return true; 
                            }
                            $inventory->removeItem(VanillaItems::DIAMOND()->setCount($this->config->getNested("full-armor.cost")));
                            $position = $sender->getPosition();
                            //Все предметы
                            $boots = $ItemsClass->getName("boots"); // Ботинки
                            $chest = $ItemsClass->getName("chest"); // Кирасса
                            $helmet = $ItemsClass->getName("helmet"); // Шлем
                            $leggins = $ItemsClass->getName("leggins"); // Штаны
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
                            return true;
                            break;
                    }
                    return false;
                }
            );
            $form->setTitle("§n§dPurchase of super armor");
            $form->addButton("§8To buy super armor", 0, "textures/items/chainmail_chestplate.png");
            $form->addButton("§7FAQ", 0, "textures/items/book_normal.png");
            $form->addButton("§o§cExit");
            $form->sendToPlayer($p);
            return true;
        }
    }
}
