<?php

namespace App\Security\Voter;

use App\Entity\Game;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class GameVoter extends Voter
{
    final public const VIEW = 'view';
    
    protected function supports($attribute, $subject): bool
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::VIEW])) {
            return false;
        }
        
        // only vote on `Game` objects
        if (!$subject instanceof Game) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var Game $game */
        $game = $subject;
        // ... (check conditions and return true to grant permission) ...
        return match ($attribute) {
            self::VIEW => $this->canView($game),
            default => false,
        };
    }

    private function canView(Game $game): bool
    {
        $tournament = $game->getTournament();
        
        switch (true) {
            // Games number 1 and 2 can be viewed if following games are not played yet (game 5, 7 and 8)
            case $game->getNumber() <= 2:
                foreach ($tournament->getGames() as $reviewedGame) {
                    if (($reviewedGame->getNumber() > 4) &&
                        ($reviewedGame->getNumber() !== 6) &&
                        ($reviewedGame->getWinner() !== null)) {
                        return false;
                    }
                }
                return true;

            // Games number 3 and 4 can be viewed if following games are not played yet (game 6, 7 and 8)
            case $game->getNumber() <= 4:
                foreach ($tournament->getGames() as $reviewedGame) {
                    if (($reviewedGame->getNumber() > 5) && ($reviewedGame->getWinner() !== null)) {
                        return false;
                    }
                }
                return true;
        
            case $game->getNumber() <= 6:
                foreach ($tournament->getGames() as $reviewedGame) {
                    if (($reviewedGame->getNumber() > 6) && ($reviewedGame->getWinner() !== null)) {
                        return false;
                    }
                }
                return true;
        
            // Finals can always be viewed
            default:
                return true;
        }
    }
}
