<?php 
require_once('include/init.php');
// echo '<pre>'; print_r($_SESSION); echo '</pre>';

// Si l'utlisateur n'est pas connecté, il n'a rien à faire sur la page profil, on le redirige vers la page index.php
if(!userConnected()){
  header('location: index.php');
}

require_once('include/header.php');
?>

  <!-- inner page section -->
  <section class="inner_page_head">
    <div class="container_fuild">
      <div class="row">
        <div class="col-md-12">
          <div class="full">
            <h3>Mes informations personnelles</h3>
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
            <div class="col-sm-12 col-md-8 col-lg-12">
              <div class="box-profil">
                <div class="detail-box d-flex align-items-center justify-content-between">
                  <h5>Prénom</h5>
                  <h6><?= $_SESSION['user']['firstName'] ?></h6>
                </div>
                <div class="detail-box d-flex align-items-center justify-content-between">
                  <h5>Nom</h5>
                  <h6><?= $_SESSION['user']['lastName'] ?></h6>
                </div>
                <div class="detail-box d-flex align-items-center justify-content-between">
                  <h5>Email</h5>
                  <h6><?= $_SESSION['user']['email'] ?></h6>
                </div>
                <div class="detail-box d-flex align-items-center justify-content-between">
                  <h5>Adresse</h5>
                  <h6><?= $_SESSION['user']['address'] ?></h6>
                </div>
                <div class="detail-box d-flex align-items-center justify-content-between">
                  <h5>Ville</h5>
                  <h6><?= $_SESSION['user']['city'] ?></h6>
                </div>
                <div class="detail-box d-flex align-items-center justify-content-between">
                  <h5>Code postal</h5>
                  <h6><?= $_SESSION['user']['zipcode'] ?></h6>
                </div>

                <?php if(adminConnected()): ?>
                  <div class="detail-box d-flex align-items-center justify-content-between">
                    <h5>Vous êtes ADMINISTRATEUR</h5>
                  </div>
                <?php endif; ?>
              </div>
            </div>
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