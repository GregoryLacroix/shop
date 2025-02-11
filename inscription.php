<?php 
require_once('include/init.php');

/*
    
    5. Afficher un message si le champ mot de passe est vide
    6. Contrôler que les mots de passe correspondent
*/

// 1. Contrôler que l'on receptionne bien toute les données saisie dans le formulaire en PHP
echo '<pre>'; print_r($_POST); echo '</pre>';

if(isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] === 'POST'){

  // 2. Contrôler la disponibilité de l'email (select + rowCount)
  // On selectionne tout dans la BDD à condition que la colonne emlail dans la BDD soit égal à l'email saisi dans le formulaire
  //                                                            gregorylacroix78@gmail.com
  $data = $connect_db->prepare("SELECT * FROM user WHERE email = :email");
  $data->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
  $data->execute();

  // echo $data->rowCount();
  // Si la condtion IF retourne TRUE, l'email est existant en BDD, on entre dans le IF
  if($data->rowCount()){
    $errorEmail = '<small class="text-color-danger">Un compte est déjà existant à cette adresse email.</small>';
  }elseif(empty($_POST['email'])){
    $errorEmail = '<small class="text-color-danger">Merci de saisir une adresse email.</small>';
  }elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
    $errorEmail = '<small class="text-color-danger">Merci de saisir une adresse email valide. (ex: exemple@gmail.com)</small>';
  }

  $password_regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/"; 
  // echo preg_match($password_regex, 'secret') . '<br>'; // returns 0
  // echo preg_match($password_regex, '-Secr3t.'); // returns 1

  if(empty($_POST['password'])){
    $errorPassword = '<small class="text-color-danger">Merci de saisir un mot de passe.</small>';
  }elseif(!preg_match($password_regex, $_POST['password'])) {
    $errorPassword = '<small class="text-color-danger">8 caractères minimum, une majuscule, une minuscule, un chiffre, un caractère spécial (#?!@$%^&*-).</small>';
  }elseif ($_POST['password'] !== $_POST['repeat_password']) {
    $errorPassword = '<small class="text-color-danger">Les mots de passe ne correspondent pas.</small>';
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
            <h3>Créer votre compte</h3>
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
          <div class="full">
            <form method="post" action="">
              <fieldset>
                <input
                  type="text"
                  placeholder="Enter votre prénom"
                  name="firstName"
                  class=""
                   />
                
                <input
                  type="text"
                  placeholder="Enter votre nom"
                  name="lastName"
                   />

                <?php if(isset($errorEmail)) echo $errorEmail; ?>   
                <input
                  type="text"
                  placeholder="Entrez votre adresse e-mail"
                  name="email"
                  class="<?php if(isset($errorEmail)) echo 'border-danger'; ?>"
                  value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>"
                   />
                <input
                  type="text"
                  placeholder="Entrer votre adresse"
                  name="address"
                   />
                <input
                  type="text"
                  placeholder="Entrer votre ville"
                  name="city"
                   />
                <input
                  type="text"
                  placeholder="Entrer votre code postal"
                  name="zipcode"
                   />
                
                <?php if(isset($errorPassword)) echo $errorPassword; ?>  
                <input
                  type="text"
                  placeholder="Enter votre mot de passe"
                  name="password"
                  class="<?php if(isset($errorPassword)) echo 'border-danger'; ?>"
                   />
                <input
                  type="text"
                  placeholder="Répétez votre mot de passe"
                  name="repeat_password"
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
?>