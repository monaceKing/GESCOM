<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Famille;

use Illuminate\Http\Request;

class FamilleController extends Controller
{

    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'type' => 'required|string',
            'code' => 'required|string',
            'intitule' => 'required|string',
            'unite_de_vente' => 'nullable|string',
            'suivi_stock' => 'nullable|string'
        ]);

        // Vérification si le code existe déjà
        $existingFamille = Famille::where('code', $request->code)->first();
        if ($existingFamille) {
            return response()->json(['message' => 'Le code existe déjà'], 400);
        }

        // Création de la famille
        $famille = new Famille;
        $famille->type = $request->type;
        $famille->code = $request->code;
        $famille->intitule = $request->intitule;
        $famille->unite_de_vente = $request->unite_de_vente;
        $famille->suivi_stock = $request->suivi_stock;

        // Enregistrement dans la base de données
        $famille->save();

        // Retourner une réponse appropriée
        return response()->json(['message' => 'Famille insérée avec succès'], 201);
    }


    public function getFamille()
    {
        $famille = Famille::all();
        return response()->json($famille, 200);
    }



    public function findByid(Request $request, $id)
    {
        $famille = Famille::find($id);

        if (!$famille) {
            return response()->json(['Message => Famille inexistante'], 404);
        }
        return response()->json($famille, 200);
    }



    public function updateFamille(Request $request, $id)
    {
        // Récupérer la famille à mettre à jour
        $famille = Famille::find($id);

        // Vérifier si la famille existe
        if (!$famille) {
            return response()->json(['message' => 'Famille inexistante'], 404);
        }

        // Valider les données
        $request->validate([
            'code' => 'required|string',
            'intitule' => 'required|string',
            'unite_de_vente' => 'required|string',
            'suivi_stock' => 'required|string'
        ]);

        // Mettre à jour les champs de la famille
        $famille->code = $request->code ?? $famille->code;
        $famille->intitule = $request->intitule ?? $famille->intitule;
        $famille->unite_de_vente = $request->unite_de_vente ?? $famille->unite_de_vente;
        $famille->suivi_stock = $request->suivi_stock ?? $famille->suivi_stock;

        // Enregistrer les modifications dans la base de données
        $famille->save();

        // Retourner une réponse appropriée
        return response()->json(['message' => 'Famille modifiée avec succès'], 200);

    }




    public function findByName($nom)
    {
        $famille = Famille::where('nom', $nom)->first();
        if (!$famille) {
            return response()->json(['Message => famille inexistante'], 404);
        }
        return response()->json([
            "statut" => "success",
            "Data" => $famille,
        ], 200);
    }



    public function supprimer(Request $request, $id)
    {
        $famille = Famille::find($id);

        if (!$famille) {
            return response()->json(['Message => Famille inexistante'], 404);
        }
        $famille->delete();
        return response()->json(['message' => 'Famille supprimée avec succès'], 200);
    }


    public function getArticlesByFamille($familleId)
    {
        // Rechercher tous les articles ayant l'id de la famille spécifié
        $articles = Article::where('famille_id', $familleId)->get();

        // Retourner les résultats sous forme de réponse JSON
        return response()->json($articles);
    }

    public function countFamilles()
    {
        $count = Famille::count();
        return response()->json($count);
    }
}
