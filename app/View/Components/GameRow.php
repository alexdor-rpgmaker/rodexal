<?php

namespace App\View\Components;

use App\Former\Game;
use App\Former\AwardSessionCategory;

use Illuminate\Support\Collection;
use Illuminate\View\View;
use Illuminate\View\Component;

class GameRow extends Component
{
    public Game $game;

    /**
     * @param Game $game
     * @return void
     */
    public function __construct($game)
    {
        $this->game = $game;
    }

    /**
     * @return View|string
     */
    public function render()
    {
        return view('components.game-row');
    }

    public function contributors(): string
    {
        return $this->game
            ->contributors
            ->map(fn ($contributor) => $contributor->linkOrName)
            ->join(", ");
    }

    public function awardedCategoriesList(): string
    {
        return $awardedCategories = $this->awardedCategories()
            ->map(fn ($award) => $this->awardName($award))
            ->join(", ");
    }

    public function nominatedCategoriesList(): string
    {
        return $this->nominatedCategories()
            ->map(fn ($award) => $award->nom_categorie)
            ->join(", ");
    }

    public function wasAwarded(): bool
    {
        return $this->awardedCategories()->isNotEmpty();
    }

    public function wasNominated(): bool
    {
        return $this->nominatedCategories()->isNotEmpty();
    }

    private function awardedCategories(): Collection
    {
        return $this->game
            ->awards
            ->filter(fn (AwardSessionCategory $award) => $award->pivot->is_vainqueur > 0);
    }

    private function nominatedCategories(): Collection
    {
        return $this->game
            ->awards
            ->filter(fn (AwardSessionCategory $award) => $award->pivot->is_vainqueur == 0);
    }

    /**
     * @param AwardSessionCategory $award
     * @return string|null
     */
    private function awardName($award): string
    {
        $awardName = $award->nom_categorie;
        if ($award->is_declinaison) {
            switch ($award->pivot->is_vainqueur) {
                case 1:
                    $awardName .= ' (or)';
                    break;
                case 2:
                    $awardName .= ' (argent)';
                    break;
                case 3:
                    $awardName .= ' (bronze)';
                    break;
                default:
                    break;
            }
        }
        return $awardName;
    }
}
