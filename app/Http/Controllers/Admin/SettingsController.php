<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    /**
     * Affiche la page des paramètres
     */
    public function index()
    {
        // Général
        $site_name = Setting::get('site_name', 'Tout Help');
        $contact_email = Setting::get('contact_email', 'contact@touthelp.com');
        $phone = Setting::get('phone', '+261 34 00 000 00');
        $address = Setting::get('address', 'Antananarivo, Madagascar');

        // Email (SMTP)
        $smtp_host = Setting::get('smtp_host', '');
        $smtp_port = Setting::get('smtp_port', '587');
        $smtp_encryption = Setting::get('smtp_encryption', 'tls');
        $smtp_email = Setting::get('smtp_email', '');
        $smtp_password_configured = Setting::get('smtp_password', '') ? '********' : '';

        // Sécurité
        $maintenance_mode = Setting::get('maintenance_mode', 'false') === 'true';
        $maintenance_message = Setting::get('maintenance_message', 'Site en maintenance. Revenez bientôt !');

        // Réseaux sociaux
        $social_facebook = Setting::get('social_facebook', '');
        $social_linkedin = Setting::get('social_linkedin', '');
        $social_instagram = Setting::get('social_instagram', '');
        $social_x = Setting::get('social_x', '');
        $social_youtube = Setting::get('social_youtube', '');

        // Légal
        $legal_mentions = Setting::get('legal_mentions', '');
        $legal_privacy = Setting::get('legal_privacy', '');
        $legal_terms = Setting::get('legal_terms', '');

        return view('admin.settings.index', compact(
            'site_name', 'contact_email', 'phone', 'address',
            'smtp_host', 'smtp_port', 'smtp_encryption', 'smtp_email', 'smtp_password_configured',
            'maintenance_mode', 'maintenance_message',
            'social_facebook', 'social_linkedin', 'social_instagram', 'social_x', 'social_youtube',
            'legal_mentions', 'legal_privacy', 'legal_terms'
        ));
    }

    /**
     * Mise à jour des informations générales
     */
    public function updateGeneral(Request $request)
    {
        $request->validate([
            'site_name' => 'nullable|string|max:255',
            'contact_email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
        ]);

        Setting::set('site_name', $request->site_name);
        Setting::set('contact_email', $request->contact_email);
        Setting::set('phone', $request->phone);
        Setting::set('address', $request->address);

        return redirect()->back()->with('success', 'Informations générales mises à jour');
    }

    /**
     * Mise à jour de la configuration email (SMTP)
     */
    public function updateEmail(Request $request)
    {
        $request->validate([
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|string|max:10',
            'smtp_encryption' => 'nullable|string|in:tls,ssl',
            'smtp_email' => 'nullable|email|max:255',
            'smtp_password' => 'nullable|string|max:255',
        ]);

        Setting::set('smtp_host', $request->smtp_host);
        Setting::set('smtp_port', $request->smtp_port);
        Setting::set('smtp_encryption', $request->smtp_encryption);
        Setting::set('smtp_email', $request->smtp_email);
        
        if ($request->filled('smtp_password')) {
            Setting::set('smtp_password', encrypt($request->smtp_password));
        }

        return redirect()->back()->with('success', 'Configuration email mise à jour');
    }

    /**
     * Test de connexion SMTP
     */
    public function testEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email'
        ]);

        try {
            $smtp_host = Setting::get('smtp_host');
            $smtp_port = Setting::get('smtp_port');
            $smtp_encryption = Setting::get('smtp_encryption');
            $smtp_email = Setting::get('smtp_email');
            $smtp_password = decrypt(Setting::get('smtp_password', ''));

            if (!$smtp_host || !$smtp_email) {
                return response()->json(['success' => false, 'message' => 'Configuration SMTP incomplète'], 400);
            }

            config([
                'mail.mailers.smtp.host' => $smtp_host,
                'mail.mailers.smtp.port' => $smtp_port,
                'mail.mailers.smtp.encryption' => $smtp_encryption,
                'mail.mailers.smtp.username' => $smtp_email,
                'mail.mailers.smtp.password' => $smtp_password,
                'mail.from.address' => $smtp_email,
                'mail.from.name' => Setting::get('site_name', 'Tout Help'),
            ]);

            Mail::raw('Test de configuration email - Tout Help', function ($message) use ($request) {
                $message->to($request->test_email)
                        ->subject('Test SMTP - Tout Help');
            });

            return response()->json(['success' => true, 'message' => 'Email de test envoyé avec succès !']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Mise à jour des paramètres de sécurité
     */
    public function updateSecurity(Request $request)
    {
        $request->validate([
            'maintenance_mode' => 'nullable|boolean',
            'maintenance_message' => 'nullable|string|max:500',
        ]);

        Setting::set('maintenance_mode', $request->has('maintenance_mode') ? 'true' : 'false');
        Setting::set('maintenance_message', $request->maintenance_message);

        return redirect()->back()->with('success', 'Paramètres de sécurité mis à jour');
    }

    /**
     * Suppression du compte admin
     */
    public function destroyAccount(Request $request)
    {
        $request->validate([
            'confirm_password' => 'required',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->confirm_password, $user->password)) {
            return redirect()->back()->withErrors(['confirm_password' => 'Mot de passe incorrect']);
        }

        Auth::logout();
        $user->delete();

        return redirect('/')->with('success', 'Votre compte a été supprimé');
    }

    /**
     * Mise à jour des réseaux sociaux
     */
    public function updateSocial(Request $request)
    {
        $request->validate([
            'social_facebook' => 'nullable|url|max:255',
            'social_linkedin' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',
            'social_x' => 'nullable|url|max:255',
            'social_youtube' => 'nullable|url|max:255',
        ]);

        Setting::set('social_facebook', $request->social_facebook);
        Setting::set('social_linkedin', $request->social_linkedin);
        Setting::set('social_instagram', $request->social_instagram);
        Setting::set('social_x', $request->social_x);
        Setting::set('social_youtube', $request->social_youtube);

        return redirect()->back()->with('success', 'Liens des réseaux sociaux mis à jour');
    }

    /**
     * Mise à jour des mentions légales
     */
    public function updateLegal(Request $request)
    {
        $request->validate([
            'legal_mentions' => 'nullable|string',
            'legal_privacy' => 'nullable|string',
            'legal_terms' => 'nullable|string',
        ]);

        Setting::set('legal_mentions', $request->legal_mentions);
        Setting::set('legal_privacy', $request->legal_privacy);
        Setting::set('legal_terms', $request->legal_terms);

        return redirect()->back()->with('success', 'Contenus légaux mis à jour');
    }

    /**
     * Mise à jour du profil administrateur
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'current_password' => 'required|current_password',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user = auth()->user();
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil administrateur mis à jour');
    }
}