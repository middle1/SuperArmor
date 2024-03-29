<?php

namespace superarmor\middle1\Data;

use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\utils\Config;
use superarmor\middle1\Data\Items;

class Enchantments {
	public function __construct(Config $config) {
		$this->config = $config;
	}

	public function getEnchantments(string $nameItem){
		$ItemsClass = new Items($this->config);
		$boots = $ItemsClass->getName("boots");
		$chest = $ItemsClass->getName("chest");
		$helmet = $ItemsClass->getName("helmet");
		$leggins = $ItemsClass->getName("leggins");
		switch($nameItem){
			case 'boots':
			$boots = $boots->addEnchantment(
								new EnchantmentInstance(
									EnchantmentIdMap::getInstance()->fromId($this->config->getNested("boots.enchants.id")),
									$this->config->getNested("boots.enchants.level")
								)
							);
		    return $boots;
			break;
			case 'chest':
			$chest = $chest->addEnchantment(
								new EnchantmentInstance(
									EnchantmentIdMap::getInstance()->fromId($this->config->getNested("chest.enchants.id")),
									$this->config->getNested("chest.enchants.level")
								)
							);
			return $chest;
			break;
			case 'helmet':
			$helmet = $helmet->addEnchantment(
								new EnchantmentInstance(
									EnchantmentIdMap::getInstance()->fromId($this->config->getNested("helmet.enchants.id")),
									$this->config->getNested("helmet.enchants.level")
								)
							);
			return $helmet;
			break;
			case 'leggins':
			$leggins = $leggins->addEnchantment(
								new EnchantmentInstance(
									EnchantmentIdMap::getInstance()->fromId($this->config->getNested("leggins.enchants.id")),
									$this->config->getNested("leggins.enchants.level")
								)
							);
			return $leggins;
			break;
			default:
			return null;
			break;
		}
	}
}
?>
