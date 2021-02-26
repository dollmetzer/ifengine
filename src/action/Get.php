<?php

namespace dollmetzer\ifengine\action;

use dollmetzer\ifengine\Action;
use dollmetzer\ifengine\ActionInterface;

class Get extends Action implements ActionInterface
{
    public function execute(): void
    {
        // recognized item word?
        if(empty($this->words['object_1'])) {
            $this->gameState->addOutput('Was soll ich nehmen?');
            return;
        }

        // does item exist?
        $item = $this->gameState->getObject($this->words['object_1']['index']);
        if(empty($item)) {
            $this->gameState->addOutput('Das finde ich hier nicht.');
            return;
        }

        // where to look
        $player = $this->gameState->getObject('_player');
        $roomIndex = $player['room'];
        if (!empty($this->words['object_2']['index'])) {
            $room = $this->gameState->getObject($this->words['object_2']['index']);
            if (!empty($room)) {
                $roomIndex = $this->words['object_2']['index'];
            }
        }

        // is item in the room?
        if ($item['room'] !== $roomIndex) {
            $this->gameState->addOutput('Das sehe ich hier nicht.');
            return;
        }

        // is item portable?
        if ($item['type'] != 'portable') {
            $this->gameState->addOutput('Das kann ich nicht mitnehmen.');
            return;
        }

        $item['room'] = '_player';
        $this->gameState->setObject($this->words['object_1']['index'], $item);
        $this->gameState->addOutput("O.K.");

    }
}