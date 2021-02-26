<?php

namespace dollmetzer\ifengine\action;

use dollmetzer\ifengine\Action;
use dollmetzer\ifengine\ActionInterface;

class Inventory extends Action implements ActionInterface
{

    public function execute(): void
    {
        $inventory = $this->gameState->getObjectsInRoom('_player');
        if(empty($inventory)) {
            $answer = 'Du hast nichts bei Dir.';
        } else {
            $answer = "Du hast folgende Gegenstände bei Dir:\n";
            foreach($inventory as $item) {
                $answer .= $item['description']."\n";
            }
        }
        $this->gameState->addOutput($answer);
    }
}