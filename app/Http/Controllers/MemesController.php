<?php

namespace App\Http\Controllers;

use App\Models\Meme;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MemesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $memes = Meme::orderBy('created_at', 'desc')->paginate(8);

        if ($request->ajax()) {
            return view('partials.meme_items', compact('memes'))->render();
        }

        return view('memes', compact('memes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('memes.create', compact('memes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|string',
        ]);

        $dataUrl = $request->input('image');

        if (!Str::startsWith($dataUrl, 'data:image/png;base64,')) {
            return response()->json(['error' => 'Format d’image invalide.'], 400);
        }

        try {
            $imageData = str_replace('data:image/png;base64,', '', $dataUrl);
            $imageData = str_replace(' ', '+', $imageData);
            $imageBinary = base64_decode($imageData);

            // Nom du fichier avec heure précise
            $fileName = 'memeflow_' . now()->format('Ymd_His') . '.png';

            Storage::disk('public')->put('memes/' . $fileName, $imageBinary);

            // Enregistrement dans la base (si ton modèle Meme a juste un champ filename)
            Meme::create([
                'filename' => $fileName,
            ]);

            return response()->json(['success' => true, 'filename' => $fileName]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la sauvegarde du mème.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Meme $memes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Meme $memes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Meme $memes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meme $memes)
    {
        //
    }
}
