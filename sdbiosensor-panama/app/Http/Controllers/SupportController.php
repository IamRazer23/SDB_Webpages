<?php

namespace App\Http\Controllers;

use App\Models\Download;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SupportController extends Controller
{
    /** TTL de caché (las descargas cambian poco). */
    private const CACHE_TTL = 600; // 10 min

    public function index(Request $request)
    {
        $category = $request->input('categoria', 'todos');
        $search   = $request->input('buscar');

        // La lista completa es pequeña: se cachea una vez y se filtra en memoria
        // (evita ir a la BD remota en cada filtro/búsqueda).
        $all = Cache::remember('downloads:all', self::CACHE_TTL, fn () =>
            Download::query()->orderByDesc('sort_order')->get()
        );

        $downloads = $all;

        if ($category && $category !== 'todos') {
            $downloads = $downloads->where('category', $category)->values();
        }

        if ($search) {
            $downloads = $downloads
                ->filter(fn ($d) => stripos($d->title, $search) !== false)
                ->values();
        }

        return view('support.index', compact('downloads', 'category', 'search'));
    }
}
