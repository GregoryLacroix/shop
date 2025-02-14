<?php 
require_once('include/init.php');

// Si l'indice 'action' est définit dans l'URL et qu'il a pour valeur 'logout', cela veut dire que l'internaute à cliqué sur le lien de déconnexion, on supprime le tableau Array de données user dans la session
if(isset($_GET['action']) && $_GET['action'] == 'logout'){

  // On ne supprime pas le fichier de session mais seulement l'indice 'user'
  unset($_SESSION['user']);
  // session_destroy()
}

// Si l'utlisateur est connecté, il n'a rien à faire sur la page identifiez-vous, on le redirige vers la page index.php
if(userConnected()){
  header('location: index.php');
}

// echo '<pre>'; print_r($_POST); echo '</pre>';

// Si on soumet le formulaire
if(isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] === 'POST'){
  // On selectionne tout dans la BDD à condition que la colonne email dans la BDD soit égal à l'email saisi dans le formulaire
  //                                                          greg@gmail.com
  $data = $connect_db->prepare("SELECT * FROM user WHERE email = :email");
  $data->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
  $data->execute();

  // Si la requete de sélection retourne un résultat, cela veut que l'email est connu en BDD
  if($data->rowCount()){
    // echo "email existant";

    // On récupère un Array contenant toutes les données de l'utilisateur qui a saisi le bon email
    $user = $data->fetch(PDO::FETCH_ASSOC);
    // echo '<pre>'; print_r($user); echo '</pre>';

    // password_verify() : fonction prédéfinie permettant de comparer le mot de passe saisi dans le formulaire a la clé de hachage du mot de passe dans la BDD, on entre dans la condition IF si les mots de passe correspondent
    if(password_verify($_POST['password'], $user['password'])){
      // echo "password valide";

      // On stock dans la session toute les données de l'utilisateur correctement authentifier, ces données sont accesibles sur n'importe quelle page du site (tant qu'on ne les supprime pas)
      //              id_user    2
      foreach($user as $key => $value){
        // $_SESSION['user']['id_user'] = 2;
        // $_SESSION['user']['firstName'] = Grégory;
        $_SESSION['user'][$key] = $value;
      }
      // echo '<pre>'; print_r($_SESSION); echo '</pre>';
      header('location: index.php');

    }else{
      // echo "password error";
      $error = '<div class="background-danger p-3 mb-3 text-white text-center">Email ou mot de passe invalide.</div>';
    }

  }else{
    // echo "email inexistant";
    $error = '<div class="background-danger p-3 mb-3 text-white text-center">Email ou mot de passe invalide.</div>';
  }
}

require_once('include/header.php');
?>

  <!-- inner page section -->
  <section class="inner_page_head">
    <div class="container_fuild">
      <div class="row">
        <div class="col-md-12">
          <div class="full">
            <h3>Identifiez-vous</h3>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end inner page section -->
  <!-- why section -->
  <section class="why_section layout_padding">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 offset-lg-2">

          <?php if(isset($_SESSION['msgRegisterValidate'])) echo $_SESSION['msgRegisterValidate']; ?>
          <?php if(isset($error)) echo $error; ?>

          <div class="full">
            <form method="post" action="">
              <fieldset>
                <input
                  type="text"
                  placeholder="Entrez votre adresse e-mail"
                  name="email"
                  class="<?php if(isset($error)) echo 'border-danger'; ?>"
                  value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>"
                  />
                <input
                  type="password"
                  placeholder="Enter votre mot de passe"
                  name="password"
                  class="<?php if(isset($error)) echo 'border-danger'; ?>"
                  />
                <input type="submit" name="submit" value="Continuer" />
              </fieldset>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end why section -->
  <!-- arrival section -->
  <!-- end arrival section -->
  <!-- footer section -->

<?php 
require_once('include/footer.php');
// On supprime le message de validation d'inscription dans la session, afin qu'il ne soit plus affiché à chaque visite sur la page d'authentification
unset($_SESSION['msgRegisterValidate']);
?>