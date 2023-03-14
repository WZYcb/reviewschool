<!DOCTYPE html>

<?php
$tabdata=chargerData("bddrs.txt");


/*
$servername = "localhost:3306";
$username = "root";
$password = "WZY28bbb";
$dbname = "semestre_ecam";


$conn = new mysqli($servername, $username, $password, $dbname);

$sql = "SELECT * FROM semestre_ecam";
$result = mysqli_query($conn, $sql);

echo "<table>";
echo "<tr><th>ID</th><th>Name</th><th>Age</th></tr>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr><td>".$row["id"]."</td><td>".$row["semestre"]."</td><td>".$row["nom_UE"]."</td><td>".$row["nom_EC"]."</td></tr>";
}
echo "</table>";


if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully";
*/

?>

<html>
  <head>
    <link rel="stylesheet" href="Styles.css">
    <link rel="icon" type="image/x-icon" href="logo_sans_fond_image_titre.png">
    <title>Review school</title>
</head>
<body>
      <div>
        <div id="gauche"><a href="ReviewSchool.php"><img id="logo" src="logo_sans_fond_image_titre.png" alt="Logo"></div></a>
        <div id="droite"><form>
        <select class="menu" name="choixecole">
	       <option selected = "yes">Choisi ton école</option>
	       <option value = "ECAM LYON">ECAM LYON</option>
        </select>

        <select class="menu" name="choixsemestre">
	       <option selected = "yes">Choisi ton semestre</option>
	       <option value = "5">Semestre 5</option>
	       <option value = "6">Semestre 6</option>
         <option value = "7">Semestre 7</option>
         <option value = "8">Semestre 8</option>
         <option value = "9">Semestre 9</option>
         <option value = "10">Semestre 10</option>
        </select>

        <input id="recherche" type="submit" value="Rechercher">
        <br><br>
        </form></div>
      </div>
<?php

// on regarde si le formulaire est vide ou non
if (isset($_GET["choixsemestre"])AND(isset($_GET["choixecole"])))
{
  //on récupère le choix du semestre dans la variable semestre et ecole dans ecole
  $SEMESTRE=$_GET["choixsemestre"];
  $ecole=$_GET["choixecole"];

  //on recupère les UE du filtre précédent dans le tableau tabUE
  $tabUE=GetUE($tabdata,$ecole,$SEMESTRE);

  //Crée un bouton pour chaque UE
  for ($i=0;$i<count($tabUE);$i++)
  {
    //si on clique sur le bouton, on modifie la variable UE en conséquences, on modifie aussi ecole et semestre car sinon elle se réinitialise
    ?>
    <div id="barreUE"><input id="UE" type="button" value = "<?php echo $tabUE[$i]?>" onclick="location.href='http://localhost/ReviewSchool/ReviewSchool.php?UE=<?php echo $tabUE[$i]?>&choixsemestre=<?php echo $SEMESTRE?>&choixecole=<?php echo $ecole?>'"></div>
  
    <?php
  }
  echo '<br><br>';
  //on verifie que le formulaire UE est plein
  if (isset($_GET["UE"]))
  {
    //on associe l'UE dans la variable UE
    $UE = $_GET["UE"];
  
    //on renseigne toute les matières de l'UE dans la table matiere
    $tabmatiere=Getmatiere($tabdata,$ecole,$SEMESTRE,$UE);

    //Crée un bouton pour chaque matière
    for ($i=0;$i<count($tabmatiere);$i++)
    {
      ?>
        <div id="barreChapitre"><input id="chapitre" type="button" value = "<?php echo $tabmatiere[$i]?>" onclick="location.href='http://localhost/ReviewSchool/ReviewSchool.php?UE=<?php echo $UE?>&choixsemestre=<?php echo $SEMESTRE?>&choixecole=<?php echo $ecole?>&choixmatiere=<?php echo $tabmatiere[$i]?>'"> <br></div>
      <?php
    }
  
  if (isset($_GET["choixmatiere"]))
  {
    
    //on recupere le choix de la matiere dans la variable matiere
    $matiere = $_GET["choixmatiere"];
    //on va ouvrir le fichier correspondant à la matiere
    ?><div id="contenu"><?php chargercontenu($matiere.".txt");?></div><?php
  }
}
}
  ?>
<?php
  
//fonction permettant de charger un fichier dans le tableau
function chargerData($fichier) {
  $lines = file($fichier);
  $tabbdd=array();
  foreach ($lines as $lineNumber => $lineContent){
    $tab = explode(";", $lineContent);
    $film = array();
    $film['matiere']=$tab[3];
    $film['UE']=$tab[2];
    $film['semestre']=$tab[1];
    $film['ecole']=$tab[0];
    $tabbdd[]=$film;
  }
  return $tabbdd;
}

//pour afficher à l'écran le contenu d'un fichier
function chargercontenu($fichier) 
{
  $lines = file($fichier);
  $tabbdd=array();
  foreach ($lines as $lineNumber => $lineContent)
  {
    $tab = explode(" ", $lineContent);
    for ($i=0;$i<count($tab);$i++)
    {
      echo $tab[$i]." ";
    }
  }
}

//fonction permettant de trier un tableau pour n'obtenir que les matiere à la fin
function GetMatiere($tabtravail, $ecole_var,$SEMESTRE_var,$UE_var) 
{
  $tabmatiereturn=array();

  for ($i=0;$i<count($tabtravail);$i++)
  {
    if (($tabtravail[$i]["ecole"]==$ecole_var)AND($tabtravail[$i]["semestre"]==$SEMESTRE_var)AND($tabtravail[$i]["UE"]==$UE_var))
    {
      $tabmatiereturn[]=$tabtravail[$i]['matiere'];
    }  
  }
  return $tabmatiereturn;
}

//fonction permettant de trier un tableau pour n'obtenir que les UE à la fin
function GetUE($tabtravail, $ecole_var,$SEMESTRE_var) 
{
  $tabuereturn=array();

  for ($i=0;$i<count($tabtravail);$i++)
  {
    if (($tabtravail[$i]["ecole"]==$ecole_var)AND($tabtravail[$i]["semestre"]==$SEMESTRE_var))
    {
      $tabuereturn[]=$tabtravail[$i]['UE'];
    }  
  }

  return $tabuereturn=array_values(array_unique($tabuereturn));//renvoi un tableau
}

//fonction permettant de trier un tableau pour n'obtenir que les semestre à la fin
function GetSemestre($tabtravail, $ecole_var) 
{
  $tabsemestrereturn=array();

  for ($i=0;$i<count($tabtravail);$i++)
  {
    if (($tabtravail[$i]["ecole"]==$ecole_var))
    {
      $tabsemestrereturn[]=$tabtravail[$i]['semestre'];
    }  
  }
 
  return  $tabsemestrereturn;
}

?>