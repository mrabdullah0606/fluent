<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Http\Response as HttpResponse;


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

    /* *************************** USERS MANAGEMENT ************************** */
    /**
     * Show the list of languages.
     *
     * @return View
     */
    public function usersIndex(): View
    {
        $users = User::where('role', '!=', 'admin')->get();
        return view('admin.content.user.index', compact('users'));
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param user $user
     * @return View
     */
    public function userEdit(User $user): View
    {
        return view('admin.content.user.edit', compact('user'));
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param User $user
     * @return void
     */
    public function userUpdate(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        $user->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'user updated successfully.');
    }

    /**
     * Remove the specified User from storage.
     *
     * @param User $user
     * @return RedirectResponse
     */
    public function userDestroy(User $user): RedirectResponse
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    // public function userDestroy(int $user): JsonResponse
    // {
    //     $response = ['result' => [], 'errors' => [], 'success' => true];
    //     $httpStatusCode = HttpResponse::HTTP_OK;
    //     try {
    //         $result = User::findOrFail($user);
    //         $response['result'] = $result->delete();
    //     } catch (\Throwable $th) {
    //         $httpStatusCode = HttpResponse::HTTP_BAD_REQUEST;
    //         $response['errors'][] = $th->getMessage();
    //         $response['success'] = false;
    //     }
    //     return response()->json(['success' => true]);
    // }
}
