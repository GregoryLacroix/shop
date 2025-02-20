<?php 
require_once('../include/init.php');
$_SESSION['msg'] = false;

// Si l'utilisateur n'est pas connecté ou est connecté mais non admin, on le redirige vers la page index.php

if(!adminConnected()){
  // header('location: ' . URL . ' index.php');
  //                  http://localhost/PHP/shop/index.php
  header('location: ' . URL . 'index.php');
}

//-------- ORDERS 

$data = $connect_db->query("
  SELECT order.id_order, user.firstName, user.lastName, order.rising, DATE_FORMAT(order.date, '%d/%m/%Y') AS dateFr, order.state
  FROM user INNER JOIN `order`
  ON user.id_user = order.user_id
  ORDER BY order.date DESC 
");

$orders = $data->fetchAll(PDO::FETCH_ASSOC);
// echo '<pre>'; print_r($orders); echo '</pre>';

$nbOrders = $data->rowCount();

if($nbOrders <= 1)
  $txt = "$nbOrders commande";
else 
  $txt = "$nbOrders commandes";


//-------- ORDERS DETAILS
if(isset($_GET['action']) && $_GET['action'] == 'details'){

  if(!isset($_GET['id']) || empty($_GET['id']))
    header('location: gestion_commande.php');

  $data = $connect_db->query("
    SELECT order_details.order_id, order_details.product_id, product.reference, product.picture, product.title, order_details.quantity, order_details.price
    FROM product INNER JOIN order_details
    ON product.id_product = order_details.product_id
    AND order_details.order_id = $_GET[id]
  ");

  $ordersDetails = $data->fetchAll(PDO::FETCH_ASSOC);
  // echo '<pre>'; print_r($ordersDetails); echo '</pre>';
}

//-------- ORDER STATE
if(isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] === 'POST'){
  // echo '<pre>'; print_r($_POST); echo '</pre>';

  if($_POST['state'] == 'sent'){
    $sentAt = Date('Y-m-d H:i:s'); 
    $data = $connect_db->prepare("UPDATE `order` SET state = :state, sentAt = :sentAt WHERE id_order = :id");
    $data->bindValue(':sentAt', $sentAt, PDO::PARAM_STR);
    $data->bindValue(':state', $_POST['state'], PDO::PARAM_STR);
    $data->bindValue(':id', $_POST['id_order'], PDO::PARAM_STR);
    $data->execute();
  }
  elseif($_POST['state'] == 'delivered'){
    $data = $connect_db->query("SELECT sentAt FROM `order` WHERE id_order = $_POST[id_order] AND sentAt IS NULL");
    var_dump($data->rowCount());
    
    if($data->rowCount() != 0){
      echo 'erreur';
      $error = true;  
    }else{
      echo 'ok';
      $sentAt = Date('Y-m-d H:i:s'); 
      $data = $connect_db->prepare("UPDATE `order` SET state = :state, deliveredAt = :deliveredAt WHERE id_order = :id");
      $data->bindValue(':deliveredAt', $sentAt, PDO::PARAM_STR);
      $data->bindValue(':state', $_POST['state'], PDO::PARAM_STR);
      $data->bindValue(':id', $_POST['id_order'], PDO::PARAM_STR);
      $data->execute();
    }
  }
  elseif($_POST['state'] == 'treatment'){
    $data = $connect_db->prepare("UPDATE `order` SET state = :state, sentAt = :sentAt, deliveredAt = :deliveredAt WHERE id_order = :id");
    $data->bindValue(':sentAt', null, PDO::PARAM_STR);
    $data->bindValue(':deliveredAt', null, PDO::PARAM_STR);
    $data->bindValue(':state', $_POST['state'], PDO::PARAM_STR);
    $data->bindValue(':id', $_POST['id_order'], PDO::PARAM_STR);
    $data->execute();
  }

  if(isset($error))
    $_SESSION['msgValidation'] = "La commande n'a pas encore été envoyée.";
  else
    $_SESSION['msgValidation'] = "L'état de la commande a été modifiée.";

  $_SESSION['msg'] = true;

  header('location: gestion_commande.php');
}

require_once('include/header.php');
?>
    <section class="section is-title-bar">
      <div class="level">
        <div class="level-left">
          <div class="level-item">
            <ul>
              <li>Admin</li>
              <li>Commandes</li>
            </ul>
          </div>
        </div>
        <!-- <div class="level-right">
            <div class="level-item">
              <div class="buttons is-right">
                <a
                  href="https://github.com/vikdiesel/admin-one-bulma-dashboard"
                  target="_blank"
                  class="button is-primary"
                >
                  <span class="icon"
                    ><i class="mdi mdi-github-circle"></i
                  ></span>
                  <span>GitHub</span>
                </a>
              </div>
            </div>
          </div> -->
      </div>
    </section>
    <section class="section is-main-section">
      <?php if(isset($_SESSION['msgValidation'])): ?>
        <div class="notification is-primary">
          <button class="delete"></button>
          <?= $_SESSION['msgValidation']; ?>
        </div>
      <?php endif; ?>
      <div class="card has-table">
        <header class="card-header">
          <p class="card-header-title">
            <span class="icon"><span class="mdi mdi-cart-outline"></span>
            </span>
            <?= $txt ?>
          </p>
          <a href="#" class="card-header-icon">
            <span class="icon"><i class="mdi mdi-reload"></i></span>
          </a>
        </header>
        <div class="card-content">
          <div class="b-table has-pagination">
            <div class="table-wrapper has-mobile-cards">
              <table
                class="table is-fullwidth is-striped is-hoverable is-fullwidth" id="table-orders">
                <thead>
                  <tr>
                    <th class="is-checkbox-cell">
                      <label class="b-checkbox checkbox">
                        <input type="checkbox" value="false" />
                        <span class="check"></span>
                      </label>
                    </th>
                    <th>N° commande</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Montant</th>
                    <th>Date</th>
                    <th>Etat</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($orders as $key => $item): ?>
                  <tr>
                    <td class="is-checkbox-cell">
                      <label class="b-checkbox checkbox">
                        <input type="checkbox" value="false" />
                        <span class="check"></span>
                      </label>
                    </td>
                    
                    <td data-label="N° commande">FAMMS<?= $item['id_order'] ?></td>
                    <td data-label="Prénom"><?= $item['firstName'] ?></td>
                    <td data-label="Nom"><?= $item['lastName'] ?></td>
                    <td data-label="Montant"><?= $item['rising'] ?>€</td>
                    <td data-label="Date">
                      <small
                        class="has-text-grey is-abbr-like"
                        title="<?= $item['dateFr'] ?>"><?= $item['dateFr'] ?></small>
                    </td>
                    <td data-label="Etat">
                    <?php
                    // if($item['state'] == 'treatment')
                    //   echo 'En cours de traitement';
                    // elseif ($item['state'] == 'sent') 
                    //   echo 'Envoyée';
                    // elseif ($item['state'] == 'delivered') 
                    //   echo 'Livrée';
                    ?>
                      <form action="" method="post">
                        <div class="field-body">
                          <div class="field is-narrow">
                            <div class="control">
                              <div class="select is-fullwidth">
                                <input type="hidden" name="id_order" value="<?= $item['id_order'] ?>">
                                <select name="state" class="formState">
                                  <option value="treatment" <?php if(isset($item['state']) && $item['state'] == "treatment") echo 'selected'; ?>>En cours de traitement</option>
                                  <option value="sent" <?php if(isset($item['state']) && $item['state'] == "sent") echo 'selected'; ?>>Envoyée</option>
                                  <option value="delivered" <?php if(isset($item['state']) && $item['state'] == "delivered") echo 'selected'; ?>>Livrée</option>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="buttons is-right">
                          <button
                            type="submit"
                            name="submit"
                            class="button is-normal is-black"
                          >
                            <span class="icon"><span class="mdi mdi-arrow-right-bold"></span></span>
                          </a>
                          </div>
                        </div>
                      </form>
                    </td>
                    <td class="is-actions-cell">
                      <div class="buttons is-right">
                        <a
                          href="?action=details&id=<?= $item['id_order'] ?>"
                          class="button is-small is-primary"
                        >
                          <span class="icon"><i class="mdi mdi-eye"></i></span>
                        </a>
                        <button
                          class="button is-small is-danger jb-modal"
                          data-target="sample-modal-<?= $item['id_order'] ?>"
                          type="button">
                          <span class="icon"><i class="mdi mdi-trash-can"></i></span>
                        </button>
                      </div>
                    </td>
                  </tr>

                  <div id="sample-modal-<?= $item['id_order'] ?>" class="modal">
                      <div class="modal-background jb-modal-close"></div>
                        <div class="modal-card">
                          <header class="modal-card-head">
                            <p class="modal-card-title">Confirmez la supression</p>
                            <button class="delete jb-modal-close" aria-label="close"></button>
                          </header>
                          <section class="modal-card-body">
                            <p>Voulez-vous réellement supprimer cette commande ?</p>
                          </section>
                          <footer class="modal-card-foot">
                            <button class="button jb-modal-close">Annuler</button>
                            <a href="?action=delete&id=<?= $item['id_order'] ?>" class="button is-danger jb-modal-close">Supprimer</a>
                          </footer>
                        </div>
                        <button
                          class="modal-close is-large jb-modal-close"
                          aria-label="close"></button>
                    </div>
                  </div>

                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            <!-- <div class="notification">
                <div class="level">
                  <div class="level-left">
                    <div class="level-item">
                      <div class="buttons has-addons">
                        <button type="button" class="button is-active">
                          1
                        </button>
                        <button type="button" class="button">2</button>
                        <button type="button" class="button">3</button>
                      </div>
                    </div>
                  </div>
                  <div class="level-right">
                    <div class="level-item">
                      <small>Page 1 of 3</small>
                    </div>
                  </div>
                </div>
              </div> -->
          </div>
        </div>
      </div>
    </section>
    
    <?php if(isset($_GET['action']) && $_GET['action'] == 'details'): ?>
    <section class="section is-main-section">
      <div class="card has-table">
        <header class="card-header">
          <p class="card-header-title">
            <span class="icon"><span class="mdi mdi-cart-arrow-down"></span>
            </span>
            Détails commande FAMMS<?= $ordersDetails[0]['order_id'] ?>
          </p>
          <a href="#" class="card-header-icon">
            <span class="icon"><i class="mdi mdi-reload"></i></span>
          </a>
        </header>
        <div class="card-content">
          <div class="b-table has-pagination">
            <div class="table-wrapper has-mobile-cards">
              <table
                class="table is-fullwidth is-striped is-hoverable is-fullwidth">
                <thead>
                  <tr>
                    <th class="is-checkbox-cell">
                      <label class="b-checkbox checkbox">
                        <input type="checkbox" value="false" />
                        <span class="check"></span>
                      </label>
                    </th>
                    <th></th>
                    <th>Référence</th>
                    <th>Titre</th>
                    <th>Quantité</th>
                    <th>Prix Unitaire</th>
                    <th>Prix total</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($ordersDetails as $key => $item): ?>
                  <tr>
                    <td class="is-checkbox-cell">
                      <label class="b-checkbox checkbox">
                        <input type="checkbox" value="false" />
                        <span class="check"></span>
                      </label>
                    </td>
                    <td class="is-image-cell">
                      <div class="image">
                        <img
                          src="<?= $item['picture'] ?>"
                          class="is-rounded" />
                      </div>
                    </td>
                    <td data-label="Référence"><?= $item['reference'] ?></td>
                    <td data-label="Titre"><?= ucfirst($item['title']) ?></td>
                    <td data-label="Quantité"><?= $item['quantity'] ?></td>
                    <th data-label="Prix unitaire"><?= $item['price'] ?>€</th>
                    <th data-label="Prix total" class="is-progress-cell"><?= $item['quantity']*$item['price'] ?>€</th>
                    <!-- <td class="is-actions-cell">
                      <div class="buttons is-right">
                        <button
                          class="button is-small is-primary"
                          type="button">
                          <span class="icon"><i class="mdi mdi-eye"></i></span>
                        </button>
                        <button
                          class="button is-small is-danger jb-modal"
                          data-target="sample-modal"
                          type="button">
                          <span class="icon"><i class="mdi mdi-trash-can"></i></span>
                        </button>
                      </div>
                    </td> -->
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            <!-- <div class="notification">
                <div class="level">
                  <div class="level-left">
                    <div class="level-item">
                      <div class="buttons has-addons">
                        <button type="button" class="button is-active">
                          1
                        </button>
                        <button type="button" class="button">2</button>
                        <button type="button" class="button">3</button>
                      </div>
                    </div>
                  </div>
                  <div class="level-right">
                    <div class="level-item">
                      <small>Page 1 of 3</small>
                    </div>
                  </div>
                </div>
              </div> -->
          </div>
        </div>
      </div>
    </section>
    <?php endif; ?>

<?php 
require_once('include/footer.php');
if($_SESSION['msg'] == false){
  unset($_SESSION['msgValidation']);
}