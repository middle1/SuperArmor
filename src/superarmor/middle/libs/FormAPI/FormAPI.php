<?php

declare(strict_types=1);

namespace superarmor\middle\libs\FormAPI;

use pocketmine\plugin\PluginBase;

class FormAPI extends PluginBase {
	/**
	 * @param callable|null $function
	 * @return CustomForm
	 * @deprecated
	 *
	 */
	public function createCustomForm(?callable $function = NULL) : CustomForm {
		return new CustomForm($function);
	}

	/**
	 * @param callable|null $function
	 * @return SimpleForm
	 * @deprecated
	 *
	 */
	public function createSimpleForm(?callable $function = NULL) : SimpleForm {
		return new SimpleForm($function);
	}

	/**
	 * @param callable|null $function
	 * @return ModalForm
	 * @deprecated
	 *
	 */
	public function createModalForm(?callable $function = NULL) : ModalForm {
		return new ModalForm($function);
	}
}
