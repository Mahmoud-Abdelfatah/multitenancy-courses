<?php

namespace App\Filament\Resources\FilamentResource\Widgets;

use Filament\Widgets\Widget;

class TeamNameWidget extends Widget
{
    protected static string $view = 'filament.resources.filament-resource.widgets.team-name-widget';

    public function getTeamName()
    {
        // Assuming your User model has a 'team' relationship
        return auth()->user()->team->name ?? 'Super admin';
    }
}
