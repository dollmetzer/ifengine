<?php


namespace dollmetzer\ifengine\action;

use dollmetzer\ifengine\Action;
use dollmetzer\ifengine\ActionInterface;

class Close extends Action implements ActionInterface
{

    public function execute(): void
    {
        $this->gameState->addOutput('execute action Close');
    }
}