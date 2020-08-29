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
use Pechenka\free\utils\TimeUtil;
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
    public static $data;
    /** @var int */
    public const ONLINE_TIME = 1296000; //1296000 = 60 * 60 * 24 * 15 = 15 дней в секундах
    
    /**
     *  Включение плагина
     */
    public function onEnable()
    {
        self::$data = new Config($this->getDataFolder() . "data.json", Config::JSON);
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
    }
    
    /**
     *  Выключение плагина, сохранение базы
     */
    public function onDisable()
    {
        self::$data->save();
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
        if (self::$data->exists($name)) {
            $time = self::$data->get($name) + (time() - EventListener::$players[$name]);
            if ($time >= self::ONLINE_TIME)
                self::givePrize($player);
            else
                $player->sendMessage("§7(§cFreeDonate§7) §fДо получения награды осталось: " . TimeUtil::toString(self::ONLINE_TIME - $time));
            return true;
        }
        self::$data->set($name, 0);
        EventListener::$players[$name] = time();
        $player->sendMessage("§7(§cFreeDonate§7) §fВы начали испытание для получения бесплатного доната!");
        return true;
    }
    
    /**
     * @param Player $player
     */
    public static function givePrize(Player $player): void
    {
        $name = $player->getLowerCaseName();
        unset(EventListener::$players[$name]);
        self::$data->remove($name);
        
        $player->sendMessage("§7(§cFreeDonate§7) §fВы провели на сервере 15 дней");
    }
}