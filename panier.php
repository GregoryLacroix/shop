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

if(isset($_POST['payForCart'])){
  $error = '';
  // echo "Panier validé";
  //                  4
  for($i = 0; $i < count($_SESSION['cart']['id_product']); $i++){
    //                                                                          10
    $data = $connect_db->query("SELECT * FROM product WHERE id_product =" . $_SESSION['cart']['id_product'][$i]);
    $product = $data->fetch(PDO::FETCH_ASSOC);
    // echo '<pre>'; print_r($product); echo '</pre>';
    
    // Si la quantité en stock en BDD est inférieur à la quantité commandée
    if($product['stock'] < $_SESSION['cart']['quantity'][$i]){

      $error .= '<div class="alert alert-danger text-center">Stock restant du produit ' . $_SESSION['cart']['title'][$i] . ' : <strong>' . $product['stock'] . '</strong></div>';

      $error .= '<div class="alert alert-warning text-center mt-2">Quantité commandée du produit ' . $_SESSION['cart']['title'][$i] . ' : <strong>' . $_SESSION['cart']['quantity'][$i] . '</strong></div>';

      // Si la quantité en stock est supérieur à 0 mais inférieur à la quantité commandée
      if($product['stock'] > 0){
        // le stock est inférieur à la quantité commandée

        // On modifie la quantité dans le fichier de session par la quantité restante en stock dans la BDD
        $_SESSION['cart']['quantity'][$i] = $product['stock'];

        $error .= '<div class="alert alert-success text-center mt-2">La quantité du produit ' . $_SESSION['cart']['title'][$i] . ' a été reduite car notre stock est insuffisant.</div>';

      }else{
        // le stock est à 0; rupture de stock, on supprime le produit de la session
        $error .= '<div class="alert alert-success text-center mt-2">Le produit ' . $_SESSION['cart']['title'][$i] . ' a été supprimé car nous sommes en rupture de stock.</div>';

        removeProductToCart($_SESSION['cart']['id_product'][$i]);
        $i--; // on décrémente la boucle après la suppression, car array_splice() supprime l'article dans les tableaux et remontent les indices inférieurs vers les indices supérieur, cela nous permet de ne pas oublié de controlé un article qui aurait changé d'indice
      }
    }
  }

  // requete insertion commande en BDD
  if(empty($error)){
    $data = $connect_db->exec("INSERT INTO `order` (user_id, rising, date, state) VALUES (" . $_SESSION['user']['id_user'] . ", " . totalAmount() . ", NOW(), 'treatment')");

    // On récupère le dernier id généré en BDD, l'id de la commande inséré en BDD pour l'enregistrer dans la table SQL order_details, afin de lié chaque produit à la bonne commande
    $idOrder = $connect_db->lastInsertId();
    // print_r($idOrder);

    for($i = 0; $i < count($_SESSION['cart']['id_product']); $i++){
      $data = $connect_db->exec("INSERT INTO `order_details` (order_id, product_id, quantity, price) VALUES ($idOrder, " . $_SESSION['cart']['id_product'][$i] . ", " . $_SESSION['cart']['quantity'][$i] . ", " . $_SESSION['cart']['price'][$i] . ")");

      $data = $connect_db->exec("UPDATE product SET stock = stock - " . $_SESSION['cart']['quantity'][$i] . " WHERE id_product = " . $_SESSION['cart']['id_product'][$i]);
    }
    unset($_SESSION['cart']);
    $_SESSION['msgValidateOrder'] = "<div class='alert alert-success text-center'>La commande a été prise en compte. Numéro de commande <strong>FAMMS$idOrder</strong></div>";
  }

}

// echo '<pre>'; print_r($_SESSION); echo '</pre>';

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
        
      <?php 
      if(isset($error)) echo $error; 
      if(isset($_SESSION['msgValidateOrder'])) echo $_SESSION['msgValidateOrder'];
      unset($_SESSION['msgValidateOrder']);
      ?>

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
            <?php if(empty($_SESSION['cart']['id_product'])): ?>

              <tr>
                <td colspan="6" class="text-center">Aucun article dans le panier</td>
              </tr>

            <?php else:
            
              //       3      4     4
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
            <?php endif; ?>
          </tbody>
        </table>
      </div>
            
      <?php if(!empty($_SESSION['cart']['id_product'])): ?>

        <div class="btn-box">
          <?php if(userConnected()): ?>

            <form action="" method="post">
              <input type="submit" name="payForCart" value="Procéder au paiement">
            </form>

          <?php else: ?>

            <p>Veuillez vous <a href="inscription.php">inscrire</a> ou vous <a href="connexion.php">identifier</a> pour valider le paiement</p>

          <?php endif; ?>
        </div>
      
      <?php endif; ?>

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