<?php
// requiert obligatoirement le fichier connect.php
require_once 'connect.php';

// j'affiche la liste de tous mes pays
$requete = 'SELECT * FROM t_pays';

if (isset($_GET["continent"])) { // si let GET Continent exist
    //alors chqnger la requette par rapport au cotinent dan le get
    $selectRegionRequete = 'SELECT * FROM t_regions WHERE continent_id =' . $_GET["continent"];
    $requete = 'SELECT * FROM t_pays WHERE  continent_id =' . $_GET["continent"];
    if ($_GET["region"]) {
        $requete = 'SELECT * FROM t_pays WHERE  continent_id =' . $_GET["continent"] . ' AND region_id =' . $_GET["region"];
    }
} else {
    // si non afficher tout les regions
    $selectRegionRequete = 'SELECT * FROM t_regions';
}

// PDO::query — Prépare et exécute une requête SQL sans marque substitutive
$statement = $db->query($requete);
// PDOStatement::fetchAll — Récupère les lignes restantes d'un ensemble de résultats
$data = $statement->fetchAll();

// Continents
$selectContinentRequete = 'SELECT * FROM t_continents';
$selectContinentQuery = $db->query($selectContinentRequete);
$selectContinent = $selectContinentQuery->fetchAll();

// Régions
$selectRegionQuery = $db->query($selectRegionRequete);
$selectRegion = $selectRegionQuery->fetchAll();

// Total
$selectTotal = 'SELECT
SUM(p.population_pays) as po,
AVG(p.taux_natalite_pays) as nat,
AVG(p.taux_mortalite_pays) as mor,
AVG(p.esperance_vie_pays) as es,
AVG(p.taux_mortalite_infantile_pays) as inf,
AVG(p.nombre_enfants_par_femme_pays) as enf,
AVG(p.taux_croissance_pays) as cr,
SUM(p.population_plus_65_pays) as pop
FROM t_pays p;';
$selectTotalQuery = $db->query($selectTotal);
$total = $selectTotalQuery->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
    <title>Population du monde</title>
    <!-- <link rel="stylesheet" href="style.css"> -->
</head>

<body class="container">
    <header>
        <h1 class="d-flex justify-content-center mt-5">Population du monde</h1>
        <form>
            <div>
                <h3>Par continent</h3>
                <select name="continent" onchange="this.form.submit()">
                    <option value="">Choisir un continent</option>
                    <?php foreach ($selectContinent as $continent) : ?>
                        <?php if (isset($_GET["continent"]) && $_GET["continent"] == $continent["id_continent"]) : ?>
                            <option value="<?php echo ($continent['id_continent']) ?>" selected><?php echo ($continent['libelle_continent']) ?></option>
                        <?php else : ?>
                            <!-- J'ai remplacé le echo par un raccourci -->
                            <option value="<?= $continent["id_continent"] ?>"><?= $continent["libelle_continent"] ?></option>
                        <?php endif ?>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="mt-3">
                <h3>Par région</h3>
                <select name="region" onchange="this.form.submit()">
                    <option value="">Choisir une région</option>
                    <?php foreach ($selectRegion as $region) : ?>
                        <?php if (isset($_GET["region"]) && $_GET["region"] == $region["id_region"]) : ?>
                            <option value="<?= ($region['id_region']) ?>" selected><?php echo ($region['libelle_region']) ?></option>
                        <?php else : ?>
                            <option value="<?= $region["id_region"] ?>"><?= $region["libelle_region"] ?></option>
                        <?php endif ?>
                    <?php endforeach ?>
                </select>
            </div>
        </form>
    </header>
    <table class="table table-striped-columns mt-5">
        <thead>
            <tr>
                <th>Pays</th>
                <th>Population totale (en milliers)</th>
                <th>Taux de natalité</th>
                <th>Taux de mortalité</th>
                <th>Espérance de vie</th>
                <th>Taux de mortalité infantile</th>
                <th>Nombre d'enfant(s) par femme</th>
                <th>Taux de croissance</th>
                <th>Population de 65 ans et plus (en milliers)</th>
            </tr>
        </thead>
        <?php foreach ($data as $datas) : ?>
            <tbody>
                <tr>
                    <td><?= $datas['libelle_pays'] ?></td>
                    <td><?= $datas['population_pays'] ?></td>
                    <td><?= $datas['taux_natalite_pays'] ?></td>
                    <td><?= $datas['taux_mortalite_pays'] ?></td>
                    <td><?= $datas['esperance_vie_pays'] ?></td>
                    <td><?= $datas['taux_mortalite_infantile_pays'] ?></td>
                    <td><?= $datas['nombre_enfants_par_femme_pays'] ?></td>
                    <td><?= $datas['taux_croissance_pays'] ?></td>
                    <td><?= $datas['population_plus_65_pays'] ?></td>
                </tr>
            <?php endforeach ?>
            </tbody>
            <tfoot>
                <tr>
                    <td>Total</td>
                    <td><?= $total['0']['po'] ?></td>
                    <td><?= $total['0']['nat'] ?></td>
                    <td><?= $total['0']['mor'] ?></td>
                    <td><?= $total['0']['es'] ?></td>
                    <td><?= $total['0']['inf'] ?></td>
                    <td><?= $total['0']['enf'] ?></td>
                    <td><?= $total['0']['cr'] ?></td>
                    <td><?= $total['0']['pop'] ?></td>
                </tr>
            </tfoot>
    </table>
</body>

</html>