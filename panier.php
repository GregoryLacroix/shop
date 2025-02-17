<?php 
require_once('include/init.php');

// echo '<pre>'; print_r($_POST); echo '</pre>';

if(isset($_POST['add_cart'])){
  $data = $connect_db->prepare("SELECT * FROM product WHERE id_product = :id");
  $data->bindValue(':id', $_POST['id_product'], PDO::PARAM_INT);
  $data->execute();

  $product = $data->fetch(PDO::FETCH_ASSOC);
  // echo '<pre>'; print_r($product); echo '</pre>';

  addProductToCart($product['id_product'], $product['title'], $product['picture'], $product['reference'], $_POST['quantity'], $product['price']);
  
  header('location: panier.php');
}
echo '<pre>'; print_r($_SESSION); echo '</pre>';

require_once('include/header.php');
?>
  <!-- inner page section -->
  <section class="inner_page_head">
    <div class="container_fuild">
      <div class="row">
        <div class="col-md-12">
          <div class="full">
            <h3>Votre panier</h3>
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
        <h2>Valider vos <span>achats !</span></h2>
      </div>
      <div class="row">
        <table class="table table-borderless">
          <thead>
            <tr>
              <th>Titre</th>
              <th>Image</th>
              <th>Référence</th>
              <th>Quantité</th>
              <th>Prix unitaire</th>
              <th>Prix total</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php 
            //            4     4
              for($i = 0; $i < count($_SESSION['cart']['id_product']); $i++): 
            ?>
                <tr>
                  <td><?= ucfirst($_SESSION['cart']['title'][$i]); ?></td>

                  <td><img src="<?= $_SESSION['cart']['picture'][$i] ?>" class="picture__product" alt="<?=  $_SESSION['cart']['title'][$i] ?>"></td>

                  <td><?= $_SESSION['cart']['reference'][$i]; ?></td>
                  <td><?= $_SESSION['cart']['quantity'][$i]; ?></td>
                  <td><?= $_SESSION['cart']['price'][$i]; ?>€</td>

                  <td><strong><?= $_SESSION['cart']['quantity'][$i]*$_SESSION['cart']['price'][$i] ?>€</strong></td>

                  <td><a href="" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a></td>
                </tr>
            <?php 
              endfor; 
            ?>
            <tr>
              <th>MONTANT TOTAL</th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th><?= totalAmount(); ?>€</th>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="btn-box">
        <?php if(userConnected()): ?>

          <form action="" method="post">
            <input type="submit" name="payForCart" value="Procéder au paiement">
          </form>

        <?php else: ?>

          <p>Veuillez vous <a href="inscription.php">inscrire</a> ou vous <a href="connexion.php">identifier</a> pour valider le paiement</p>
          
        <?php endif; ?>
      </div>

      <div class="btn-box">
        <a href="product.php"> Continuer vos achats </a>
      </div>
    </div>
  </section>
  <!-- end product section -->
  <!-- footer section -->
   
<?php 
require_once('include/footer.php');
?>