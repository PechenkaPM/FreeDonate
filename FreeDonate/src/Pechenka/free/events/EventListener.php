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

namespace Pechenka\free\events;

use Pechenka\free\FreeDonate;
use Pechenka\free\utils\TimeUtil;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

/**
 * Class EventListener
 *
 * @package Pechenka\free\events
 */
class EventListener implements Listener
{
    
    /** @var FreeDonate */
    private $plugin;
    /** @var array */
    public static $players = [];
    
    /**
     * EventListener constructor.
     *
     * @param FreeDonate $plugin
     */
    public function __construct(FreeDonate $plugin)
    {
        $this->plugin = $plugin;
    }
    
    /**
     * @param PlayerJoinEvent $event
     */
    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getLowerCaseName();
        if (!$this->plugin->data->exists($name)) return;
        
        if (($time = $this->plugin->data->get($name)) >= 1296000) {   //1296000 = 60 * 60 * 24 * 15(15 дней в секундах)
            $player->sendMessage("§7(§cFreeDonate§7) §fВы провели на сервере 15 дней");
            return;
        }
        self::$players[$name] = time();
        $player->sendMessage("§7(§cFreeDonate§7) §fДо получения награды осталось: ".TimeUtil::toString(1296000 - $time));
    }
    
    /**
     * @param PlayerQuitEvent $event
     */
    public function onQuit(PlayerQuitEvent $event)
    {
        $name = $event->getPlayer()->getLowerCaseName();
        if (!isset(self::$players[$name])) return;
        
        $data = $this->plugin->data->get($name);
        $data += time() - self::$players[$name];
        $this->plugin->data->set($name, $data);
    }
}