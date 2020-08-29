<?php

/**
 * ██████╗ ███████╗ █████╗ ██╗  ██╗███████╗███╗  ██╗██╗  ██╗ █████╗
 * ██╔══██╗██╔════╝██╔══██╗██║  ██║██╔════╝████╗ ██║██║ ██╔╝██╔══██╗
 * ██████╔╝█████╗  ██║  ╚═╝███████║█████╗  ██╔██╗██║█████═╝ ███████║
 * ██╔═══╝ ██╔══╝  ██║  ██╗██╔══██║██╔══╝  ██║╚████║██╔═██╗ ██╔══██║
 * ██║     ███████╗╚█████╔╝██║  ██║███████╗██║ ╚███║██║ ╚██╗██║  ██║
 * ╚═╝     ╚══════╝ ╚════╝ ╚═╝  ╚═╝╚══════╝╚═╝  ╚══╝╚═╝  ╚═╝╚═╝  ╚═╝
 *
 * DEVELOPED BY 𝗣𝗘𝗖𝗛𝗘𝗡𝗞𝗔
 * VK: 𝓿𝓴.𝓬𝓸𝓶/𝓿𝓸𝓿𝓪𝓷446
 */

namespace Pechenka\free;

use Pechenka\free\events\EventListener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

/**
 * Class FreeDonate
 *
 * @package Pechenka
 */
class FreeDonate extends PluginBase
{
    
    /** @var Config */
    public $data;
    
    /**
     *  Включение плагина
     */
    public function onEnable()
    {
        $this->data = new Config($this->getDataFolder() . "data.json", Config::JSON);
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    }
    
    /**
     *  Выключение плагина, сохранение базы
     */
    public function onDisable()
    {
        $this->data->save();
    }
    
    /**
     * @param CommandSender|Player $player
     * @param Command $command
     * @param string $label
     * @param array $args
     * @return bool
     */
    public function onCommand(CommandSender $player, Command $command, string $label, array $args): bool
    {
        $name = $player->getLowerCaseName();
        if ($this->data->exists($name)) {
            $player->sendMessage("§7(§cFreeDonate§7) §fВы не можете начать испытание еще раз");
            return true;
        }
        $this->data->set($name, 0);
        EventListener::$players[$name] = time();
        $player->sendMessage("§7(§cFreeDonate§7) §fВы начали испытание для бесплатного доната!");
        return true;
    }
}