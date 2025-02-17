<?php 
require_once('include/init.php');

// Si l'indice '?id=' est définit dans l'URL 
if(isset($_GET['id'])){
  $data = $connect_db->prepare("SELECT * FROM product WHERE id_product = :id");
  $data->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
  $data->execute();

  // Si la requete ne retourne aucun résultat, on redirige l'utilisateur vers la page index.php
  if(!$data->rowCount()){
    header('location: index.php');
  }

  $product = $data->fetch(PDO::FETCH_ASSOC);
  // echo '<pre>'; print_r($product); echo '</pre>';

}else {
  // Sinon on redirige l'internaute
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
            <h3>Informations de l'article</h3>
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
        <h2>Il est fait pour <span>vous !</span></h2>
      </div>
      <div class="row">
        <div class="col-sm-6 col-md-4 col-lg-4">
          <div class="box">
            <div class="img-box">
              <img src="<?= $product['picture'] ?>" alt="<?= $product['title'] ?>" />
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-md-8 col-lg-8 pt-5">
          <div class="detail-box">
            <h5><?= ucfirst($product['title']) ?></h5>
            <h6>Référence : <?= $product['reference'] ?></h6>
            <h6>Catégorie : <?= $product['category'] ?></h6>
            <h6>Taille : <?= $product['size'] ?></h6>
            <h6>Genre : <?= $product['public'] ?></h6>
            <h6>Couleur : <?= $product['color'] ?></h6>
            <h6>Description : <?= $product['description'] ?></h6>
            <h5><?= $product['price'] ?>€</h5>

            <?php if($product['stock'] > 0): ?>
              
              <form action="panier.php" method="post" class="d-flex align-items-center justify-content-start">
                <input type="hidden" name="id_product" value="<?= $product['id_product'] ?>">
                <!-- <label for="quantity">Qté</label> -->
                <select name="quantity" id="quantity" class="form-control col-2 mr-2">
                  <!--              6            500              5             -->
                  <?php for($i = 1; $i <= $product['stock'] && $i <= 10; $i++): ?>
                    <option value="<?= $i ?>"><?= $i ?></option>
                  <?php endfor; ?>
                </select>
                <input type="submit" name="add_cart" value="Ajouter au panier" class="m-0">
              </form>

            <?php else: ?>
              <strong class="error-text-color">Y'en avait mais y'en a plus !</strong>
            <?php endif; ?>

          </div>
        </div>
      </div>
      <div class="btn-box">
        <a href=""> Voir tout les produits </a>
      </div>
    </div>
  </section>
  <!-- end product section -->
  <!-- footer section -->
   
<?php 
require_once('include/footer.php');
?>