<?php
namespace App\Models;
use App\Core\Db;

class Model extends Db
{
    //Table de la base données
    protected $table;

    //Instance de Db
    private $db;


    public function findAll(){
        $query = $this->requete('SELECT * FROM ' . $this->table);
        return$query->fetchAll();
    }

    public function findBy(array $criteres)
    {
        $champ = [];
        $valeurs = [];

        //On boucle pour éclater les tableaux
        foreach ($criteres as $champ => $valeur) {
            //SELECT * FROM annonces WHERE actif = ? AND signalé = 0
            //BindValues(1, valeurs)
            $champs[] = "$champ = ?";
            $valeurs[] = $valeur;
        }

        //On transforme le tableau champ en une chaine de caractères
        $liste_champs = implode(' AND ', $champs);
        //On execute la requete
        return $this->requete('SELECT * FROM ' . $this->table . ' WHERE ' . $liste_champs, $valeurs)->fetchAll();
    }

    public function find(int $id)
    {
        return $this->requete("SELECT * FROM {$this->table} WHERE id = $id")->fetch();
    }

    public function create(Model $model)
    {
        $champ = [];
        $inter = [];
        $valeurs = [];

        //On boucle pour éclater les tableaux
        foreach ($model as $champ => $valeur) {
            //INSERT INTO annonces (titre, decsription, actif) VALUES (?, ?, ?)
            if ($valeur != null && $champ != 'db' && $champ != 'table') {
                $champs[] = $champ;
                $inter[] = "?";
                $valeurs[] = $valeur;
            }
            
        }

        //On transforme le tableau champ en une chaine de caractères
        $liste_champs = implode(', ', $champs);
        $liste_inter = implode(', ', $inter);

        //On execute la requete
        return $this->requete('INSERT INTO ' . $this->table . ' (' . $liste_champs .') VALUES(' . $liste_inter . ')', $valeurs);
    }

    public function update(int $id, Model $model)
    {
        $champ = [];
        $valeurs = [];

        //On boucle pour éclater les tableaux
        foreach ($model as $champ => $valeur) {
            //UPDATE annonces SET titre = ?, decsription = ?, actif = ? WHERE id = ?
            if ($valeur !== null && $champ != 'db' && $champ != 'table') {
                $champs[] = "$champ = ?";
                $valeurs[] = $valeur;
            }
            
        }
        $valeurs [] = $id;

        //On transforme le tableau champ en une chaine de caractères
        $liste_champs = implode(', ', $champs);

        //On execute la requete
        return $this->requete('UPDATE ' . $this->table . ' SET ' . $liste_champs . ' WHERE id = ?', $valeurs);
    }

    public function hydrate(array $donnees)
    {
        foreach ($donnees as $key => $value) {
            //On récupère le nom du setter correspondant à la clé (key)
            //titre -> setTitre
            $setter = 'set'.ucfirst($key);

            //On verifie si le setter existe
            if (method_exists($this, $setter)) {
                //On apelle le setter 
                $this->$setter($value);
            }
        }
        return $this;
    }

    public function delete(int $id)
    {
        return $this->requete("DELETE FROM {$this->table} WHERE id = ?", [$id]);
    }

    public function requete(string $sql, array $attributs = null)
    {
        //On récupère l'instance de Db
        $this->db = Db::getInstance();

        //On vérifie si on a des attributs
        if ($attributs !== null) {
            //Requete préparée
            $query = $this->db->prepare($sql);
            $query->execute($attributs);
            return $query;
        }else{
            //Requete simple
            return $this->db->query($sql);
        }
    }
}