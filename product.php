<?php 
require_once('include/init.php');

/*
  Exo: Afficher les produits stockés en BDD
  1. Sélectionner l'ensemble de la table product
  2. Excécuter une méthode (fecth / fetchAll) pour rendre le résultat exploitable sous forme d'Array
  3. Traitement pour l'affichage (boucle)
  4. Prévoir un lien qui redirige vers la page fiche_produit.php pour chaque produit, avec envoi de l'id_product dans l'URL   
*/

require_once('include/header.php');
?>

  <!-- inner page section -->
  <section class="inner_page_head">
    <div class="container_fuild">
      <div class="row">
        <div class="col-md-12">
          <div class="full">
            <h3>Grille de produits</h3>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end inner page section -->
  <!-- product section -->
  <section class="product_section layout_padding">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>Nos <span>produits</span></h2>
      </div>
      <div class="row">

      
        <div class="col-sm-6 col-md-4 col-lg-3">
          <div class="box">
            <div class="option_container">
              <div class="options">
                <a href="" class="option1"> Chemise homme </a>
                <a href="" class="option2">Acheter maintenant</a>
              </div>
            </div>
            <div class="img-box">
              <img src="assets/images-famma/p1.png" alt="" />
            </div>
            <div class="detail-box">
              <h5>Chemise homme</h5>
              <h6>75€</h6>
            </div>
          </div>
        </div>

      </div>
      <div class="btn-box">
        <a href=""> Voir tous les produits </a>
      </div>
    </div>
  </section>
  <!-- end product section -->
  <!-- footer section -->
 
<?php 
require_once('include/footer.php');
?>
