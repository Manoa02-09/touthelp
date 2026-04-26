<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Catalogue;
use App\Models\Formation;
use App\Models\Article;
use App\Models\Partenaire;
use App\Models\Avis;
use App\Models\Message;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    /**
     * Recherche globale API — Moteur complet
     */
    public function api(Request $request)
    {
        $q = $request->query('q', '');
        
        // Validation basique
        if (strlen($q) < 2) {
            return response()->json([
                'catalogues' => [],
                'formations' => [],
                'articles' => [],
                'partenaires' => [],
                'avis' => [],
                'messages' => [],
            ]);
        }

        // Nettoyer la query (sécurité)
        $q = trim($q);
        $searchTerm = "%{$q}%";

        $results = [
            'catalogues' => [],
            'formations' => [],
            'articles' => [],
            'partenaires' => [],
            'avis' => [],
            'messages' => [],
        ];

        /* ════════════════════════════════════════
           CATALOGUES — Recherche complète
        ════════════════════════════════════════ */
        $results['catalogues'] = Catalogue::where(function($query) use ($searchTerm) {
                $query->where('titre', 'like', $searchTerm)
                      ->orWhere('description', 'like', $searchTerm)
                      ->orWhere('objectifs', 'like', $searchTerm)
                      ->orWhere('competences', 'like', $searchTerm)
                      ->orWhere('public_cible', 'like', $searchTerm);
            })
            ->select('id', 'titre', 'description', 'created_at', 'updated_at')
            ->limit(6)
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'type' => 'catalogue',
                'title' => $this->highlight($item->titre, $q),
                'subtitle' => $this->truncate($item->description, 70),
                'url' => route('admin.catalogues.edit', $item),
                'badge' => '📚 Catalogue',
                'meta' => 'Modifié: ' . $item->updated_at->diffForHumans(),
            ]);

        /* ════════════════════════════════════════
           FORMATIONS — Recherche complète
        ════════════════════════════════════════ */
        $results['formations'] = Formation::where(function($query) use ($searchTerm) {
                $query->where('titre', 'like', $searchTerm)
                      ->orWhere('description', 'like', $searchTerm)
                      ->orWhere('description_courte', 'like', $searchTerm)
                      ->orWhere('lieu', 'like', $searchTerm)
                      ->orWhere('formateur_name', 'like', $searchTerm)
                      ->orWhere('contenu_details', 'like', $searchTerm);
            })
            ->select('id', 'titre', 'description_courte', 'date_debut', 'lieu', 'nombre_places', 'created_at')
            ->limit(6)
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'type' => 'formation',
                'title' => $this->highlight($item->titre, $q),
                'subtitle' => $item->date_debut ? $item->date_debut->format('d/m/Y') . ' • ' . $item->lieu : $item->lieu,
                'url' => route('admin.formations.edit', $item),
                'badge' => '📅 Formation',
                'meta' => ($item->nombre_places ? $item->nombre_places . ' places' : 'Illimité'),
            ]);

        /* ════════════════════════════════════════
           ARTICLES / BLOG — Recherche complète
        ════════════════════════════════════════ */
        $results['articles'] = Article::where(function($query) use ($searchTerm) {
                $query->where('titre', 'like', $searchTerm)
                      ->orWhere('contenu', 'like', $searchTerm)
                      ->orWhere('extrait', 'like', $searchTerm)
                      ->orWhere('auteur', 'like', $searchTerm)
                      ->orWhere('tags', 'like', $searchTerm);
            })
            ->select('id', 'titre', 'extrait', 'date_publication', 'auteur', 'statut')
            ->limit(6)
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'type' => 'article',
                'title' => $this->highlight($item->titre, $q),
                'subtitle' => ($item->date_publication ? $item->date_publication->format('d/m/Y') : 'À publier') . ' • ' . ($item->auteur ?? 'Admin'),
                'url' => route('admin.articles.edit', $item),
                'badge' => '📝 Article',
                'meta' => $this->getStatusLabel($item->statut),
            ]);

        /* ════════════════════════════════════════
           PARTENAIRES — Recherche complète
        ════════════════════════════════════════ */
        $results['partenaires'] = Partenaire::where(function($query) use ($searchTerm) {
                $query->where('nom_entreprise', 'like', $searchTerm)
                      ->orWhere('description', 'like', $searchTerm)
                      ->orWhere('secteur_activite', 'like', $searchTerm)
                      ->orWhere('contact_email', 'like', $searchTerm)
                      ->orWhere('contact_telephone', 'like', $searchTerm);
            })
            ->select('id', 'nom_entreprise', 'description', 'secteur_activite', 'created_at')
            ->limit(6)
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'type' => 'partenaire',
                'title' => $this->highlight($item->nom_entreprise, $q),
                'subtitle' => $item->secteur_activite ?? 'Secteur non spécifié',
                'url' => route('admin.partenaires.edit', $item),
                'badge' => '🤝 Partenaire',
                'meta' => 'Ajouté: ' . $item->created_at->diffForHumans(),
            ]);

        /* ══════════���═════════════════════════════
           AVIS CLIENTS — Recherche complète
        ════════════════════════════════════════ */
        $results['avis'] = Avis::where(function($query) use ($searchTerm) {
                $query->where('entreprise_nom', 'like', $searchTerm)
                      ->orWhere('contenu', 'like', $searchTerm)
                      ->orWhere('auteur_nom', 'like', $searchTerm)
                      ->orWhere('fonction', 'like', $searchTerm);
            })
            ->select('id', 'entreprise_nom', 'contenu', 'note', 'date_avis', 'auteur_nom')
            ->limit(6)
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'type' => 'avis',
                'title' => $this->highlight($item->entreprise_nom, $q),
                'subtitle' => $this->renderStars($item->note) . ' • ' . ($item->date_avis ? $item->date_avis->format('d/m/Y') : 'Date inconnue'),
                'url' => route('admin.avis.edit', $item),
                'badge' => '⭐ Avis Client',
                'meta' => $item->auteur_nom ? 'Par: ' . $item->auteur_nom : 'Anonyme',
            ]);

        /* ════════════════════════════════════════
           MESSAGES — Recherche complète
        ════════════════════════════════════════ */
        $results['messages'] = Message::where(function($query) use ($searchTerm) {
                $query->where('nom_complet', 'like', $searchTerm)
                      ->orWhere('email_client', 'like', $searchTerm)
                      ->orWhere('message', 'like', $searchTerm)
                      ->orWhere('sujet', 'like', $searchTerm)
                      ->orWhere('reponse_admin', 'like', $searchTerm)
                      ->orWhere('telephone', 'like', $searchTerm);
            })
            ->select('id', 'nom_complet', 'email_client', 'message', 'lu', 'repondu', 'created_at')
            ->limit(10)
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'type' => 'message',
                'title' => $this->highlight($item->nom_complet, $q) . ' (' . $item->email_client . ')',
                'subtitle' => $this->truncate($item->message, 60),
                'url' => route('admin.discu') . '?msg_id=' . $item->id,
                'badge' => '💬 Message',
                'meta' => $this->getMessageStatus($item),
            ]);

        return response()->json($results);
    }

    /**
     * Highlight le terme de recherche dans le texte
     */
    private function highlight(string $text, string $query): string
    {
        if (strlen($query) < 2) {
            return $text;
        }

        $pattern = preg_quote($query, '/');
        return preg_replace(
            "/$pattern/i",
            '<mark>$0</mark>',
            $text
        );
    }

    /**
     * Truncate le texte à une longueur max
     */
    private function truncate(?string $text, int $length = 80): string
    {
        if (!$text) {
            return 'N/A';
        }

        $clean = strip_tags($text);
        $clean = preg_replace('/\s+/', ' ', $clean);

        if (strlen($clean) > $length) {
            return Str::limit($clean, $length, '...');
        }

        return $clean;
    }

    /**
     * Affiche le statut d'un article
     */
    private function getStatusLabel(?string $status): string
    {
        return match($status) {
            'publie' => '✓ Publié',
            'brouillon' => '📝 Brouillon',
            'archive' => '📦 Archivé',
            default => 'Statut inconnu',
        };
    }

    /**
     * Statut du message
     */
    private function getMessageStatus($message): string
    {
        if ($message->repondu) {
            return '✓ Répondu';
        }

        if ($message->lu) {
            return '👀 Lu';
        }

        return '🔴 Nouveau';
    }

    /**
     * Affiche les étoiles pour un avis
     */
    private function renderStars(int $note): string
    {
        $stars = str_repeat('⭐', $note);
        $empty = str_repeat('☆', 5 - $note);
        return $stars . $empty . ' (' . $note . '/5)';
    }
}