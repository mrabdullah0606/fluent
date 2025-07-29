<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Home page.
     *
     * @return View
     */
    function index(): View
    {
        $languages = Language::withCount('teachers')->get();
        return view('website.content.index', compact('languages'));
    }


    public function showByLanguage($languageId): View
    {
        $language = Language::findOrFail($languageId);

        $teachers = Teacher::with('user')
            ->where('language_id', $languageId)
            ->get();

        return view('website.content.language', compact('language', 'teachers'));
    }

    /**
     * About page.
     *
     * @return View
     */
    public function about(): View
    {
        return view('website.content.about');
    }
    /**
     * Messages page.
     *
     * @return View
     */
    public function messages(): View
    {
        return view('website.content.messages');
    }

    public function calendar(): View
    {
        return view('website.content.calendar');
    }

    /**
     * Contact page.
     *
     * @return View
     */
    public function contact(): View
    {
        return view('website.content.contact');
    }

    /**
     * Find Tutor page.
     *
     * @return View
     * 
     */
    public function findTutor(): View
    {
        return view('website.content.find-tutor');
    }

    public function adminLogin(): View
    {
        return view('auth.admin.login');
    }
}
