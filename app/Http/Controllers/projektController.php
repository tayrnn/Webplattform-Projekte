<?php
// ============================================================
// DATEI-PFAD:  app/Http/Controllers/ProjektController.php
//
// Nur der index()-Teil wird gezeigt – füge es in deinen
// bestehenden Controller ein.
// ============================================================

public function index(Request $request)
{
    $query = Projekt::query();

    // Filter: Status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Filter: Kategorie
    if ($request->filled('kategorie')) {
        $query->where('kategorie', $request->kategorie);
    }

    // Suchbegriff (falls du eine Suche hast)
    if ($request->filled('suche')) {
        $query->where(function ($q) use ($request) {
            $q->where('titel', 'like', '%' . $request->suche . '%')
              ->orWhere('beschreibung', 'like', '%' . $request->suche . '%');
        });
    }

    $projekte = $query->latest()->paginate(12)->withQueryString();
    //                                         ↑ wichtig: behält Filter-Parameter beim Blättern

    return view('projekte.index', [
        'projekte'        => $projekte,
        'activeStatus'    => $request->status    ?? '',
        'activeKategorie' => $request->kategorie ?? '',
    ]);
}