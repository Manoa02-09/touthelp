<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $email = Setting::get('contact_email');
        return view('admin.settings.index', compact('email'));
    }

    public function update(Request $request)
    {
        $request->validate(['contact_email' => 'required|email']);
        Setting::set('contact_email', $request->contact_email);
        return redirect()->route('admin.settings.index')->with('success', 'Email modifié avec succès.');
    }
}