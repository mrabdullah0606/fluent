<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class AdminController extends Controller
{

    /**
     * Show student login form.
     */
    public function showLoginForm()
    {
        return view('auth.admin.login');
    }

    /**
     * Handle student login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt(array_merge($credentials, ['role' => 'admin']), $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Show the admin dashboard.
     */
    public function index(): Response
    {
        return response()->view('admin.content.dashboard');
    }

    /* *************************** LANGUAGE MANAGEMENT ************************** */
    /**
     * Show the list of languages.
     *
     * @return View
     */
    public function languagesIndex(): View
    {
        $languages = Language::all();
        return view('admin.content.language.index', compact('languages'));
    }

    /**
     * Show the form for creating a new language.
     *
     * @return View
     */
    public function languageCreate(): View
    {
        return view('admin.content.language.create');
    }

    /**
     * Store a newly created language in storage.
     *
     * @param Request $request
     * @return void
     */
    public function languagesStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:languages,name',
            'symbol' => 'required|string|unique:languages,symbol',
        ]);

        Language::create([
            'name' => $request->name,
            'symbol' => $request->symbol,
        ]);

        return redirect()->route('admin.languages.index')->with('success', 'Language created successfully.');
    }

    /**
     * Show the form for editing the specified language.
     *
     * @param Language $language
     * @return View
     */
    public function languagesEdit(Language $language): View
    {
        return view('admin.content.language.edit', compact('language'));
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param Language $language
     * @return void
     */
    public function languagesUpdate(Request $request, Language $language): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|unique:languages,name,' . $language->id,
            'symbol' => 'required|string|unique:languages,symbol,' . $language->id,
        ]);

        $language->update([
            'name' => $request->name,
            'symbol' => $request->symbol,
        ]);

        return redirect()->route('admin.languages.index')->with('success', 'Language updated successfully.');
    }

    /**
     * Remove the specified language from storage.
     *
     * @param Language $language
     * @return RedirectResponse
     */
    public function languagesDestroy(Language $language): RedirectResponse
    {
        $language->delete();
        return redirect()->route('admin.languages.index')->with('success', 'Language deleted successfully.');
    }
}
