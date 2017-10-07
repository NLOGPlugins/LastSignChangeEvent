<?php

declare(strict_types=1);

namespace nlog\LastSignChangeEvent;

use pocketmine\event\Event;
use pocketmine\event\Cancellable;
use pocketmine\tile\Sign;
use pocketmine\Player;

class LastSignChangeEvent extends Event implements Cancellable {
	
	public static $handlerList = null;
	
	private $sign, $player;
	
	public function __construct(Sign $sign, Player $player) {
		$this->sign = $sign;
		$this->player = $player;
	}
	
	public function setLine(int $index, string $line) {
		$this->sign->setLine($index, $line);
	}
	
	public function getLine(int $index) {
		return $this->sign->getLine($index);
	}
	
	public function getPlayer() : Player {
		return $this->player;
	}
	
	public function getSign() : Sign {
		return $this->sign;
	}
	
}