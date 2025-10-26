<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ChirpController extends Controller
{

    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chirps = Chirp::with('user')
            ->latest()
            ->take(50)
            ->get();

        return view('home', ['chirps' => $chirps]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate(
            [
                'message' => 'required|string|max:255'
            ],
            [
                'message.required' => 'У Чирпа должен быть контент!',
                'message.max' => 'Чирп не может быть длиньше 255 символов!',
            ]
        );

        // Create the chirp 
        auth()->user()->chirps()->create($validated);

        return redirect('/')->with('success', 'Ваш Чирп запостен !');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp)
    {
        $this->authorize('update', $chirp);
        return view('chirps.edit', compact('chirp'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp)
    {

        $this->authorize('update', $chirp);

        // Validate the request
        $validated = $request->validate(
            [
                'message' => 'required|string|max:255'
            ],
            [
                'message.required' => 'У Чирпа должен быть контент!',
                'message.max' => 'Чирп не может быть длиньше 255 символов!',
            ]
        );

        // Create the chirp 
        $chirp->update($validated);

        return redirect('/')->with('success', 'Ваш Чирп обновлён !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chirp $chirp)
    {
        $this->authorize('delete', $chirp);
        $chirp->delete();

        return redirect('/')->with('success', 'Ваш Чирп удалён!');
    }
}
