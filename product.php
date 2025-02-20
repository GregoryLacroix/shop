<?php 
require_once('include/init.php');
$_SESSION['msg'] = false;

$data = $connect_db->query("SELECT id_product, title, picture, price FROM product");
$products = $data->fetchAll(PDO::FETCH_ASSOC);
// echo '<pre>'; print_r($products); echo '</pre>';

if(isset($_GET['action']) && $_GET['action'] == 'addCart'){
  $data = $connect_db->query("SELECT id_product, title, picture, reference, price FROM product WHERE id_product = $_GET[id]");
  $product = $data->fetch(PDO::FETCH_ASSOC);
  // echo '<pre>'; print_r($product); echo '</pre>';

  $quantity = 1;
  addProductToCart($product['id_product'], $product['title'], $product['picture'], $product['reference'], $quantity, $product['price']);

  $_SESSION['msgAddProductCartShop'] = '<div class="bg-success p-3 text-white text-center">L\'article a été ajouté au panier.</div>';
  $_SESSION['msg'] = true;

  header('location: product.php');
}

require_once('include/header.php');
if(isset($_SESSION['msgAddProductCartShop'])) echo $_SESSION['msgAddProductCartShop'];
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

        <?php foreach($products as $item): ?>

        <div class="col-sm-6 col-md-4 col-lg-3">
          <div class="box">
            <div class="option_container">
              <div class="options">
                <a href="fiche_produit.php?id=<?= $item['id_product'] ?>" class="option1">En savoir plus</a>
                <a href="?action=addCart&id=<?= $item['id_product'] ?>" class="option2">Ajouter au panier</a>
              </div>
            </div>
            <div class="img-box">
              <img src="<?= $item['picture'] ?>" alt="<?= $item['title'] ?>" />
            </div>
            <div class="detail-box">
              <h5><?= $item['title'] ?></h5>
              <h6><?= $item['price'] ?>€</h6>
            </div>
          </div>
        </div>

        <?php endforeach; ?>

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
if($_SESSION['msg'] == false){
  unset($_SESSION['msgAddProductCartShop']);
}
?>
