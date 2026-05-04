<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TeamMemberController extends Controller
{
    public function index(): View
    {
        $teamMembers = TeamMember::query()
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.team-members.index', compact('teamMembers'));
    }

    public function create(): View
    {
        return view('admin.team-members.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateTeamMember($request);

        $validated['slug'] = $this->makeUniqueSlug($validated['slug'] ?: $validated['name']);
        $validated['is_active'] = $request->has('is_active');

        TeamMember::query()->create($validated);

        return redirect()
            ->route('admin.team-members.index')
            ->with('success', 'Ekip üyesi başarıyla oluşturuldu.');
    }

    public function edit(int $id): View
    {
        $teamMember = TeamMember::query()->findOrFail($id);

        return view('admin.team-members.edit', compact('teamMember'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $teamMember = TeamMember::query()->findOrFail($id);

        $validated = $this->validateTeamMember($request);

        $validated['slug'] = $this->makeUniqueSlug($validated['slug'] ?: $validated['name'], $teamMember->id);
        $validated['is_active'] = $request->has('is_active');

        $teamMember->update($validated);

        return redirect()
            ->route('admin.team-members.index')
            ->with('success', 'Ekip üyesi başarıyla güncellendi.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $teamMember = TeamMember::query()->findOrFail($id);
        $teamMember->delete();

        return redirect()
            ->route('admin.team-members.index')
            ->with('success', 'Ekip üyesi başarıyla silindi.');
    }

    private function validateTeamMember(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'bio' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:500'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:100'],
            'linkedin_url' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer'],

            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'canonical_url' => ['nullable', 'string', 'max:500'],
            'robots_index' => ['required', 'string', 'max:20'],
            'robots_follow' => ['required', 'string', 'max:20'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string', 'max:500'],
            'og_image' => ['nullable', 'string', 'max:500'],
            'schema_type' => ['nullable', 'string', 'max:100'],
        ]);
    }

    private function makeUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $slug = Str::slug($value);

        if ($slug === '') {
            $slug = 'ekip-uyesi';
        }

        $originalSlug = $slug;
        $counter = 2;

        while (
            TeamMember::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
