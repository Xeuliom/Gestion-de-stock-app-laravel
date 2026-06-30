<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\Fournisseur;
use App\Models\MouvementStock;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Utilisateurs ────────────────────────────────────────────
        $admin = User::create([
            'name'     => 'Administrateur',
            'username' => 'admin',
            'email'    => 'admin@gestionstock.fr',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);

        $magasinier = User::create([
            'name'     => 'Mohamed Magasinier',
            'username' => 'magasinier',
            'email'    => 'magasinier@gestionstock.fr',
            'password' => Hash::make('magasinier123'),
            'role'     => 'magasinier',
        ]);

        // ── Catégories ───────────────────────────────────────────────
        $cats = [
            ['nom' => 'Informatique',       'description' => 'Matériel et accessoires informatiques'],
            ['nom' => 'Fournitures bureau', 'description' => 'Papeterie et fournitures de bureau'],
            ['nom' => 'Mobilier',           'description' => 'Bureaux, chaises et rangements'],
            ['nom' => 'Électronique',       'description' => 'Appareils et composants électroniques'],
            ['nom' => 'Consommables',       'description' => 'Encre, toner et consommables'],
        ];
        $categories = collect($cats)->map(fn($c) => Categorie::create($c));

        // ── Fournisseurs ─────────────────────────────────────────────
        $fournisseurs = [
            Fournisseur::create([
                'nom'       => 'TechDistrib Maroc',
                'contact'   => 'Karim Alaoui',
                'telephone' => '+212 522 345 678',
                'email'     => 'contact@techdistrib.ma',
                'adresse'   => 'Bd Mohammed V, Casablanca',
            ]),
            Fournisseur::create([
                'nom'       => 'Bureau Plus',
                'contact'   => 'Fatima Benali',
                'telephone' => '+212 537 123 456',
                'email'     => 'info@bureauplus.ma',
                'adresse'   => 'Avenue Hassan II, Rabat',
            ]),
            Fournisseur::create([
                'nom'       => 'Global Fournitures',
                'contact'   => 'Ahmed Idrissi',
                'telephone' => '+212 539 876 543',
                'email'     => 'ventes@globalfournitures.ma',
                'adresse'   => 'Zone Industrielle, Tanger',
            ]),
        ];

        // ── Produits ─────────────────────────────────────────────────
        $produits = [
            ['ref' => 'ORD-001', 'nom' => 'Ordinateur portable HP',      'cat' => 0, 'four' => 0, 'achat' => 8500,  'vente' => 10500, 'qte' => 15, 'seuil' => 3],
            ['ref' => 'ORD-002', 'nom' => 'Ordinateur portable Dell',     'cat' => 0, 'four' => 0, 'achat' => 9200,  'vente' => 11500, 'qte' => 8,  'seuil' => 2],
            ['ref' => 'ECR-001', 'nom' => 'Écran 24" Samsung',            'cat' => 0, 'four' => 0, 'achat' => 2800,  'vente' => 3500,  'qte' => 20, 'seuil' => 5],
            ['ref' => 'CLV-001', 'nom' => 'Clavier sans fil Logitech',    'cat' => 0, 'four' => 0, 'achat' => 350,   'vente' => 500,   'qte' => 30, 'seuil' => 10],
            ['ref' => 'SOU-001', 'nom' => 'Souris optique sans fil',      'cat' => 0, 'four' => 0, 'achat' => 180,   'vente' => 280,   'qte' => 25, 'seuil' => 8],
            ['ref' => 'PAP-001', 'nom' => 'Ramette papier A4 (500 feu.)', 'cat' => 1, 'four' => 1, 'achat' => 45,    'vente' => 65,    'qte' => 3,  'seuil' => 10],
            ['ref' => 'STY-001', 'nom' => 'Stylos bille bleus (boîte)',   'cat' => 1, 'four' => 1, 'achat' => 25,    'vente' => 40,    'qte' => 50, 'seuil' => 10],
            ['ref' => 'CAR-001', 'nom' => 'Cartouche encre Canon noire',  'cat' => 4, 'four' => 2, 'achat' => 120,   'vente' => 180,   'qte' => 2,  'seuil' => 5],
            ['ref' => 'TON-001', 'nom' => 'Toner HP LaserJet',            'cat' => 4, 'four' => 2, 'achat' => 350,   'vente' => 500,   'qte' => 4,  'seuil' => 3],
            ['ref' => 'BUR-001', 'nom' => 'Bureau en L 160cm',            'cat' => 2, 'four' => 1, 'achat' => 2200,  'vente' => 3200,  'qte' => 5,  'seuil' => 2],
            ['ref' => 'CHA-001', 'nom' => 'Chaise ergonomique de bureau', 'cat' => 2, 'four' => 1, 'achat' => 1800,  'vente' => 2500,  'qte' => 10, 'seuil' => 3],
            ['ref' => 'IMP-001', 'nom' => 'Imprimante laser HP',          'cat' => 3, 'four' => 0, 'achat' => 3500,  'vente' => 4800,  'qte' => 6,  'seuil' => 2],
        ];

        $createdProduits = [];
        foreach ($produits as $p) {
            $createdProduits[] = Produit::create([
                'reference'          => $p['ref'],
                'nom'                => $p['nom'],
                'categorie_id'       => $categories[$p['cat']]->id,
                'fournisseur_id'     => $fournisseurs[$p['four']]->id,
                'prix_achat'         => $p['achat'],
                'prix_vente'         => $p['vente'],
                'quantite_disponible'=> $p['qte'],
                'seuil_alerte'       => $p['seuil'],
            ]);
        }

        // ── Mouvements de démonstration ───────────────────────────────
        $mouvements = [
            ['produit' => 0, 'type' => 'entree', 'qte' => 10, 'motif' => 'Commande initiale', 'jours' => 30],
            ['produit' => 1, 'type' => 'entree', 'qte' => 5,  'motif' => 'Réapprovisionnement', 'jours' => 25],
            ['produit' => 5, 'type' => 'entree', 'qte' => 20, 'motif' => 'Stock initial', 'jours' => 20],
            ['produit' => 0, 'type' => 'sortie', 'qte' => 2,  'motif' => 'Affectation service comptabilité', 'jours' => 15],
            ['produit' => 2, 'type' => 'sortie', 'qte' => 3,  'motif' => 'Nouveaux postes de travail', 'jours' => 10],
            ['produit' => 5, 'type' => 'sortie', 'qte' => 17, 'motif' => 'Distribution mensuelle', 'jours' => 5],
            ['produit' => 7, 'type' => 'entree', 'qte' => 5,  'motif' => 'Achat cartouches', 'jours' => 3],
            ['produit' => 7, 'type' => 'sortie', 'qte' => 3,  'motif' => 'Utilisation imprimantes', 'jours' => 1],
        ];

        foreach ($mouvements as $m) {
            MouvementStock::create([
                'produit_id'     => $createdProduits[$m['produit']]->id,
                'type'           => $m['type'],
                'quantite'       => $m['qte'],
                'motif'          => $m['motif'],
                'user_id'        => $m['type'] === 'entree' ? $admin->id : $magasinier->id,
                'date_mouvement' => now()->subDays($m['jours']),
            ]);
        }
    }
}
