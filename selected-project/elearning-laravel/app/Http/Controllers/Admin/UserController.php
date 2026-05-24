<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatutCompte;
use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use App\Services\Admin\AdminValidationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index', [
            'users' => Utilisateur::query()
                ->with(['candidat', 'formateur'])
                ->latest()
                ->paginate(20),
            'statuses' => StatutCompte::cases(),
        ]);
    }

    public function updateStatus(Request $request, Utilisateur $user, AdminValidationService $service): RedirectResponse
    {
        $validated = $request->validate([
            'statut' => ['required', Rule::enum(StatutCompte::class)],
        ]);

        $service->updateUserStatus($user, StatutCompte::from($validated['statut']));

        return back()->with('status', 'Statut utilisateur mis a jour.');
    }
}
