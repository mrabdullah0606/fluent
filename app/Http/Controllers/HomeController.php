<?php

namespace App\Http\Controllers;

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
        return view('website.content.index');
    }

    /**
     * About page.
     *
     * @return View
     */
    function about(): View
    {
        return view('website.content.about');
    }
    /**
     * Messages page.
     *
     * @return View
     */
    function messages(): View
    {
        return view('website.content.messages');
    }

    function calender(): View
    {
        return view('website.content.calender');
    }

    /**
     * Contact page.
     *
     * @return View
     */
    function contact(): View
    {
        return view('website.content.contact');
    }

    /**
     * Find Tutor page.
     *
     * @return View
     * 
     */
    function findTutor(): View
    {
        return view('website.content.find-tutor');
    }

    function adminLogin(): View
    {
        return view('auth.admin.login');
    }
}
