<?php 
require_once('include/init.php');
// echo '<pre>'; print_r($_SESSION); echo '</pre>';

// Si l'utlisateur n'est pas connecté, il n'a rien à faire sur la page profil, on le redirige vers la page index.php
if(!userConnected()){
  header('location: index.php');
}

$data = $connect_db->query("
  SELECT DISTINCT(`order`.id_order) 
  FROM user INNER JOIN `order`
  ON user.id_user = order.user_id
  AND user.id_user = " . $_SESSION['user']['id_user'] . " ORDER BY order.id_order DESC"
);
$iDorders = $data->fetchAll(PDO::FETCH_ASSOC);

if(isset($_POST['order']) && $_SERVER['REQUEST_METHOD'] === 'POST'){
  $data = $connect_db->query("
    SELECT `order`.*, product.*, order_details.*
    FROM user INNER JOIN `order`
    ON user.id_user = order.user_id
    INNER JOIN order_details
    ON order.id_order = order_details.order_id 
    INNER JOIN product
    ON order_details.product_id = product.id_product
    AND user.id_user = " . $_SESSION['user']['id_user'] . " 
    AND order_details.order_id = $_POST[order]
  ");
}else{

  $data = $connect_db->query("SELECT COUNT(*) AS nbOrderDetails FROM order_details GROUP BY order_details.order_id ORDER BY order_details.order_id DESC LIMIT 1");
  $nbOrderDetails = $data->fetch(PDO::FETCH_ASSOC);

  $data = $connect_db->query("
    SELECT `order`.*, product.*, order_details.*
    FROM user INNER JOIN `order`
    ON user.id_user = order.user_id
    INNER JOIN order_details
    ON order.id_order = order_details.order_id 
    INNER JOIN product
    ON order_details.product_id = product.id_product
    AND user.id_user = " . $_SESSION['user']['id_user'] . " ORDER BY order_details.order_id DESC LIMIT $nbOrderDetails[nbOrderDetails]
  ");

  $lastOrder = true;
}

$order = $data->fetchAll(PDO::FETCH_ASSOC);
// echo '<pre>'; print_r($order); echo '</pre>';
// echo '<pre>'; print_r($_POST); echo '</pre>';

require_once('include/header.php');
?>

  <!-- inner page section -->
  <section class="inner_page_head">
    <div class="container_fuild">
      <div class="row">
        <div class="col-md-12">
          <div class="full">
            <h3>Mes commandes</h3>
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
                <form action="" method="post" class="d-flex align-items-center justify-content-start mb-4">
                  <select name="order" id="order" class="form-control col-7 mr-2 rounded-0" onchange="this.form.submit()">
                      <option value="">-- Sélectionner une commande --</option>
                      <?php foreach($iDorders as $key => $item): ?>
                        <option value="<?= $item['id_order'] ?>">FAMMS<?= $item['id_order'] ?></option>
                      <?php endforeach; ?>
                  </select>
                </form>

                <?php 
                  $date = new DateTimeImmutable($order[0]['date']);
                  $dateFr = $date->format('d/m/Y'); 

                  

                  $date = new DateTimeImmutable($order[0]['sentAt']);
                  $dateSent = $date->format('d/m/Y'); 
                  if(isset($lastOrder)): 
                  // print_r($dateFr);
                ?>
                  <h3 class="mb-0">Votre dernière commande n° FAMMS<?= $order[0]['id_order']; ?></h3>
                <?php else: ?>
                  <h3 class="mb-0">Commande n° FAMMS<?= $order[0]['id_order']; ?></h3>
                <?php endif; ?>
                <small class="font-italic">Commande passé le <?= $dateFr ?></small><br>
                <small class="font-italic">Statut : 
                  <strong class="text-success">
                  <?php 
                  if($order[0]['state'] == 'treatment'){
                    echo 'en cours de traitement';
                  }
                  elseif($order[0]['state'] == 'sent'){
                    $date = new DateTimeImmutable($order[0]['sentAt']);
                    $dateSent = $date->format('d/m/Y');
                    echo 'envoyée le ' . $dateSent;
                  }
                  elseif($order[0]['state'] == 'delivered'){
                    $date = new DateTimeImmutable($order[0]['deliveredAt']);
                    $dateDelivered = $date->format('d/m/Y');
                    echo 'livrée le ' . $dateDelivered;
                  }
                  ?>
                  </strong>
                </small>
                
              <div class="row mt-3">
                <table class="table table-borderless">
                  <thead>
                    <tr>
                      <th>Image</th>
                      <th>Titre</th>
                      <th>Référence</th>
                      <th>Quantité</th>
                      <th>Prix unitaire</th>
                      <th>Prix total</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php foreach($order as $key => $item): ?>
                    <tr>
                      <td>
                        <img src="<?= $item['picture'] ?>" class="picture_order" alt="<?= $item['title'] ?>">
                      </td>
                      <td><?= $item['title'] ?></td>
                      <td><?= $item['reference'] ?></td>
                      <td><?= $item['quantity'] ?></td>
                      <th><?= $item['price'] ?>€</th>
                      <th><?= $item['quantity']*$item['price'] ?>€</th>
                    <tr>
                  <?php endforeach; ?>
                  <tr>
                    <th colspan="2">MONTANT TOTAL</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th><?= $order[0]['rising']; ?>€</th>
                  </tr>
                  </tbody>
                </table>
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