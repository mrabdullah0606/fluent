<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\Career;
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

    // Show the form to create a new user
    public function userCreate(): View
    {
        return view('admin.content.user.create');
    }

    // Store the newly created user
    public function userStore(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:student,teacher', // or whatever roles you support
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_verified' => true, // default verified, adjust as needed
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:student,teacher',
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // Only hash and update password if it's filled
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }
    // public function userUpdate(Request $request, User $user): RedirectResponse
    // {
    //     $request->validate([
    //         'name' => 'required|string'
    //     ]);

    //     $user->update([
    //         'name' => $request->name,
    //     ]);

    //     return redirect()->route('admin.users.index')->with('success', 'user updated successfully.');
    // }

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


    /* ************************************************************************** */
    /*                              CAREER MANAGEMENT                             */
    /* ************************************************************************** */

    /**
     * Show the list of careers.
     *
     * @return View
     */
    public function careersIndex(): View
    {
        $careers = Career::all();
        return view('admin.content.career.index', compact('careers'));
    }

    /**
     * Show the list of careers.
     *
     * @return View
     */
    public function careerCreate(): View
    {
        $careers = Career::all();
        return view('admin.content.career.create', compact('careers'));
    }

    public function careerStore(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0',
            'type' => 'required|in:full_time,part_time,contract',
            'description' => 'required|string',
            'is_active' => 'required|boolean',
        ]);

        Career::create($request->only([
            'title',
            'location',
            'salary',
            'type',
            'description',
            'is_active',
        ]));

        return redirect()->route('admin.careers.index')->with('success', 'Job added successfully!');
    }

    public function careerEdit(Career $career): View
    {
        return view('admin.content.career.edit', compact('career'));
    }

    public function careerUpdate(Request $request, Career $career): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0',
            'type' => 'required|in:full_time,part_time,contract',
            'description' => 'required|string',
            'is_active' => 'required|boolean',
        ]);

        $career->update($request->only([
            'title',
            'location',
            'salary',
            'type',
            'description',
            'is_active',
        ]));

        return redirect()->route('admin.careers.index')->with('success', 'Job updated successfully!');
    }

    public function careerDestroy(Career $career): RedirectResponse
    {
        $career->delete();
        return redirect()->route('admin.careers.index')->with('success', 'Job deleted successfully!');
    }

    /* ************************************************************************** */
    /*                            APPLICANT MANAGEMENT                            */
    /* ************************************************************************** */
    /**
     * Show the list of careers.
     *
     * @return View
     */
    public function applicantsIndex(): View
    {
        $applicants = Applicant::with('career')->get();
        // dd($applicants->toArray());
        return view('admin.content.applicant.index', compact('applicants'));
    }

    public function applicantsEdit(Applicant $applicant): View
    {
        return view('admin.content.applicant.edit', compact('applicant'));
    }

    public function applicantsUpdate(Request $request, Applicant $applicant): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,accepted,rejected',
        ]);

        $applicant->update([
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.applicants.index')->with('success', 'Applicant status updated successfully!');
    }
    public function applicantsDestroy(Applicant $applicant): RedirectResponse
    {
        $applicant->delete();
        return redirect()->route('admin.applicants.index')->with('success', 'Applicant deleted successfully!');
    }
}
