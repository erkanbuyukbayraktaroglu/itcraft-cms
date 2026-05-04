<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SliderController extends Controller
{
    public function index(): View
    {
        $sliders = Slider::query()
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.sliders.index', compact('sliders'));
    }

    public function create(): View
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateSlider($request);

        $validated['is_active'] = $request->has('is_active');

        Slider::query()->create($validated);

        return redirect()
            ->route('admin.sliders.index')
            ->with('success', 'Slider başarıyla oluşturuldu.');
    }

    public function edit(int $id): View
    {
        $slider = Slider::query()->findOrFail($id);

        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $slider = Slider::query()->findOrFail($id);

        $validated = $this->validateSlider($request);

        $validated['is_active'] = $request->has('is_active');

        $slider->update($validated);

        return redirect()
            ->route('admin.sliders.index')
            ->with('success', 'Slider başarıyla güncellendi.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $slider = Slider::query()->findOrFail($id);
        $slider->delete();

        return redirect()
            ->route('admin.sliders.index')
            ->with('success', 'Slider başarıyla silindi.');
    }

    private function validateSlider(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:500'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_url' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer'],
        ]);
    }
}
