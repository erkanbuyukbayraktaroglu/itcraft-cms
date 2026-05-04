<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function index(): View
    {
        $teamMembers = TeamMember::query()
            ->active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(12);

        return view('frontend.team.index', compact('teamMembers'));
    }

    public function show(string $slug): View
    {
        $teamMember = TeamMember::query()
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        $otherTeamMembers = TeamMember::query()
            ->active()
            ->where('id', '!=', $teamMember->id)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->limit(6)
            ->get();

        return view('frontend.team.show', compact('teamMember', 'otherTeamMembers'));
    }
}
