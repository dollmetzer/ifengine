<?php


namespace dollmetzer\ifengine\action;

use dollmetzer\ifengine\Action;
use dollmetzer\ifengine\ActionInterface;

class Examine extends Action implements ActionInterface
{

    public function execute(): void
    {
        if(empty($this->words['object_1'])) {
            $answer = $this->getRoomDescription();
        } else {
            $answer = $this->getObjectDescription($this->words['object_1']);
        }

        $this->gameState->addOutput($answer);
    }

    private function getObjectDescription($object) {
        $item = $this->gameState->getObject($object['index']);
        $player = $this->gameState->getObject('_player');
        if (($player['room'] != $item['room']) && ('_player' != $item['room'])) {
            return 'Das ist hier nicht.';
        }
        $answer = $item['description'];

        $items = $this->gameState->getObjectsInRoom($object['index']);
        if (!empty($items)) {
            $answer .= "\nHier ist folgendes: \n";
            foreach($items as $item) {
                $answer .= $item['name'];
            }
        }

        return $answer;
    }

    private function getRoomDescription() {
        $player = $this->gameState->getObject('_player');
        $room = $this->gameState->getObject($player['room']);
        $answer = $room['description'];
        $items = $this->gameState->getObjectsInRoom($player['room']);
        if (!empty($items)) {
            $answer .= "\nHier ist folgendes: \n";
            foreach($items as $item) {
                $answer .= $item['name'];
            }
        }
        return $answer;
    }
}