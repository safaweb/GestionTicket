<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
class TicketController extends Controller
{
    public function showFilePreview($filePath)
    {
        // Déterminez le type de fichier pour fournir la prévisualisation appropriée
        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

        if (in_array($fileExtension, ['jpg', 'jpeg', 'png'])) {
            // Affichage des images
            return '<img src="' . asset('storage/' . $filePath) . '" alt="Image preview" style="max-width: 100%; height: auto;">';
        } elseif ($fileExtension == 'pdf') {
            // Affichage des PDF
            return '<a href="' . asset('storage/' . $filePath) . '" target="_blank">Voir le document PDF</a>';
        } elseif (in_array($fileExtension, ['mp4', 'mov'])) {
            // Affichage des vidéos
            return '<video controls style="max-width: 100%;"><source src="' . asset('storage/' . $filePath) . '" type="video/' . $fileExtension . '"></video>';
        } else {
            // Affichage des autres fichiers
            return '<a href="' . asset('storage/' . $filePath) . '" download>Télécharger le fichier</a>';
        }
    }
}
