<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(): View
    {
        $menus = Menu::query()
            ->with(['items' => function ($query) {
                $query->whereNull('parent_id')
                    ->orderBy('sort_order')
                    ->orderBy('title');
            }, 'items.children'])
            ->orderBy('id')
            ->get();

        return view('admin.menus.index', compact('menus'));
    }

    public function create(): View
    {
        return view('admin.menus.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable'],
        ]);

        $slug = $validated['slug'] ?: Str::slug($validated['name']);

        if ($slug === '') {
            $slug = 'menu';
        }

        $originalSlug = $slug;
        $counter = 2;

        while (Menu::query()->where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        Menu::query()->create([
            'name' => $validated['name'],
            'slug' => $slug,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()
            ->route('admin.menus.index')
            ->with('success', 'Menü başarıyla oluşturuldu.');
    }

    public function edit(int $id): View
    {
        $menu = Menu::query()
            ->with(['items' => function ($query) {
                $query->whereNull('parent_id')
                    ->orderBy('sort_order')
                    ->orderBy('title');
            }, 'items.children'])
            ->findOrFail($id);

        $allItems = MenuItem::query()
            ->where('menu_id', $menu->id)
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        return view('admin.menus.edit', compact('menu', 'allItems'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $menu = Menu::query()->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable'],
        ]);

        $slug = $validated['slug'] ?: Str::slug($validated['name']);

        if ($slug === '') {
            $slug = 'menu';
        }

        $originalSlug = $slug;
        $counter = 2;

        while (
            Menu::query()
                ->where('slug', $slug)
                ->where('id', '!=', $menu->id)
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $menu->update([
            'name' => $validated['name'],
            'slug' => $slug,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()
            ->route('admin.menus.edit', $menu->id)
            ->with('success', 'Menü başarıyla güncellendi.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $menu = Menu::query()->withCount('allItems')->findOrFail($id);

        if (in_array($menu->slug, ['header', 'footer'], true)) {
            return back()->with('error', 'Header ve footer ana menüleri silinemez. Pasif hale getirebilirsin.');
        }

        if ($menu->all_items_count > 0) {
            return back()->with('error', 'Bu menüye bağlı menü elemanları var. Önce menü elemanlarını silmelisin.');
        }

        $menu->delete();

        return redirect()
            ->route('admin.menus.index')
            ->with('success', 'Menü başarıyla silindi.');
    }

    public function storeItem(Request $request, int $menuId): RedirectResponse
    {
        $menu = Menu::query()->findOrFail($menuId);

        $validated = $request->validate([
            'parent_id' => ['nullable', 'integer', 'exists:menu_items,id'],
            'title' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'max:500'],
            'target' => ['required', 'string', 'max:20'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable'],
        ]);

        MenuItem::query()->create([
            'menu_id' => $menu->id,
            'parent_id' => $validated['parent_id'] ?: null,
            'title' => $validated['title'],
            'url' => $validated['url'],
            'target' => $validated['target'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()
            ->route('admin.menus.edit', $menu->id)
            ->with('success', 'Menü elemanı başarıyla eklendi.');
    }

    public function editItem(int $itemId): View
    {
        $item = MenuItem::query()->findOrFail($itemId);

        $menu = Menu::query()->findOrFail($item->menu_id);

        $allItems = MenuItem::query()
            ->where('menu_id', $menu->id)
            ->where('id', '!=', $item->id)
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        return view('admin.menus.item-edit', compact('item', 'menu', 'allItems'));
    }

    public function updateItem(Request $request, int $itemId): RedirectResponse
    {
        $item = MenuItem::query()->findOrFail($itemId);

        $validated = $request->validate([
            'parent_id' => ['nullable', 'integer', 'exists:menu_items,id'],
            'title' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'max:500'],
            'target' => ['required', 'string', 'max:20'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable'],
        ]);

        $parentId = $validated['parent_id'] ?: null;

        if ((int) $parentId === (int) $item->id) {
            $parentId = null;
        }

        $item->update([
            'parent_id' => $parentId,
            'title' => $validated['title'],
            'url' => $validated['url'],
            'target' => $validated['target'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()
            ->route('admin.menus.edit', $item->menu_id)
            ->with('success', 'Menü elemanı başarıyla güncellendi.');
    }

    public function destroyItem(int $itemId): RedirectResponse
    {
        $item = MenuItem::query()->with('children')->findOrFail($itemId);
        $menuId = $item->menu_id;

        if ($item->children()->count() > 0) {
            return back()->with('error', 'Bu menü elemanının alt menüleri var. Önce alt menüleri silmelisin.');
        }

        $item->delete();

        return redirect()
            ->route('admin.menus.edit', $menuId)
            ->with('success', 'Menü elemanı başarıyla silindi.');
    }
}
