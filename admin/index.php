<?php 
require_once('../include/init.php');

// Si l'utilisateur n'est pas connecté ou est connecté mais non admin, on le redirige vers la page index.php

if(!adminConnected()){
  // header('location: ' . URL . ' index.php');
  //                  http://localhost/PHP/shop/index.php
  header('location: ' . URL . 'index.php');
}

$data = $connect_db->query("SELECT COUNT(*) AS nbClient FROM user WHERE roles != 'admin'");
$nbClient = $data->fetch(PDO::FETCH_ASSOC);

$data = $connect_db->query("SELECT SUM(rising) AS nbSales FROM `orders`");
$nbSales = $data->fetch(PDO::FETCH_ASSOC); 

$data = $connect_db->query("
  SELECT product.title
  FROM product INNER JOIN orders_details
  WHERE orders_details.product_id = product.id_product
  GROUP BY orders_details.product_id
  ORDER BY COUNT(orders_details.product_id) DESC LIMIT 0,1
");
$bestSale = $data->fetch(PDO::FETCH_ASSOC);

$data = $connect_db->query("SELECT id_product, picture, title, stock FROM product WHERE stock <= 20 ORDER BY stock");
$products = $data->fetchAll(PDO::FETCH_ASSOC); 

$nbProduct = $data->rowCount();
if($nbProduct <= 1)
  $txt = "$nbProduct article stock insuffisant";
else 
  $txt = "$nbProduct articles stock insuffisant";

// echo '<pre>'; print_r($products); echo '</pre>';

require_once('include/header.php');
?>
    <section class="section is-title-bar">
      <div class="level">
        <div class="level-left">
          <div class="level-item">
            <ul>
              <li>Admin</li>
              <li>Dashboard</li>
            </ul>
          </div>
        </div>
      </div>
    </section>
    <section class="hero is-hero-bar">
      <div class="hero-body">
        <div class="level">
          <div class="level-left">
            <div class="level-item">
              <h1 class="title">Dashboard</h1>
            </div>
          </div>
          <div class="level-right" style="display: none">
            <div class="level-item"></div>
          </div>
        </div>
      </div>
    </section>
    <section class="section is-main-section">
      <div class="tile is-ancestor">
        <div class="tile is-parent">
          <div class="card tile is-child">
            <div class="card-content">
              <div class="level is-mobile">
                <div class="level-item">
                  <div class="is-widget-label">
                    <h3 class="subtitle is-spaced">Clients</h3>
                    <h1 class="title"><?= $nbClient['nbClient'] ?></h1>
                  </div>
                </div>
                <div class="level-item has-widget-icon">
                  <div class="is-widget-icon">
                    <span class="icon has-text-primary is-large"><i class="mdi mdi-account-multiple mdi-48px"></i></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="tile is-parent">
          <div class="card tile is-child">
            <div class="card-content">
              <div class="level is-mobile">
                <div class="level-item">
                  <div class="is-widget-label">
                    <h3 class="subtitle is-spaced">Ventes</h3>
                    <h1 class="title"><?= $nbSales['nbSales'] ?>€</h1>
                  </div>
                </div>
                <div class="level-item has-widget-icon">
                  <div class="is-widget-icon">
                    <span class="icon has-text-info is-large"><i class="mdi mdi-cart-outline mdi-48px"></i></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="tile is-parent">
          <div class="card tile is-child">
            <div class="card-content">
              <div class="level is-mobile">
                <div class="level-item">
                  <div class="is-widget-label">
                    <h3 class="subtitle is-spaced">Meilleur vente</h3>
                    <h1 class="title"><?= ucfirst($bestSale['title']) ?></h1>
                  </div>
                </div>
                <div class="level-item has-widget-icon">
                  <div class="is-widget-icon">
                    <span class="icon has-text-success is-large"><i class="mdi mdi-finance mdi-48px"></i></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

       <div class="card has-table">
        <header class="card-header">
          <p class="card-header-title">
            <span class="icon"><span class="mdi mdi-shopping-outline"></span>
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
                class="table is-fullwidth is-striped is-hoverable is-fullwidth" id="table-product-stock">
                <thead>
                  <tr>
                    <th class="is-checkbox-cell">
                      <label class="b-checkbox checkbox">
                        <input type="checkbox" value="false" />
                        <span class="check"></span>
                      </label>
                    </th>
                    <?php //              10
                    for($i = 0; $i < $data->columnCount(); $i++):
                        $dataColumn = $data->getColumnMeta($i);
                        // echo '<pre>'; print_r($dataColumn); echo '</pre>';  
                        if($dataColumn['name'] != 'id_product'):
                          if($dataColumn['name'] == 'stock'):

                    ?>
                        <th class="has-text-centered"><?= ucfirst($dataColumn['name']) ?></th>
                      <?php else: ?>
                        <th class="has-text-left"><?= ucfirst($dataColumn['name']) ?></th>

                    <?php 
                          endif;
                        endif;
                    endfor; 
                    ?>
                    <th class="has-text-right">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($products as $arrayProduct): ?>
                  <tr>
                    <td class="is-checkbox-cell">
                      <label class="b-checkbox checkbox">
                        <input type="checkbox" value="false" />
                        <span class="check"></span>
                      </label>
                    </td>
                    
                    <?php 
                    foreach($arrayProduct as $key => $value):
                      if($key != 'id_product'):
                    ?>

                      <?php if($key == 'picture'): ?>
                        <td data-label="<?= ucfirst($key) ?>">
                          <img src="<?= $value ?>" class="picture__product" alt="<?= $arrayProduct['title'] ?>">
                        </td>
                      <?php elseif($key == 'stock' && $value <= 10): ?>
                        <td data-label="<?= ucfirst($key) ?>" class="is-danger has-text-centered is-vcentered">
                          <?= "<strong class=''>$value</strong>" ?>
                        </td>
                      <?php elseif($key == 'stock' && $value <= 20): ?>
                        <td data-label="<?= ucfirst($key) ?>" class="is-warning has-text-centered is-vcentered">
                          <?= "<strong class=''>$value</strong>" ?>
                        </td>
                      <?php else: ?>
                        <td data-label="<?= ucfirst($key) ?>" class="has-text-left is-vcentered">
                          <?= $value ?>
                        </td>
                      <?php endif; ?>

                    <?php 
                      endif;
                    endforeach; 
                    ?>
                   
                    <td class="is-actions-cell is-vcentered">
                      <div class="buttons is-right">
                        <a
                          href="gestion_boutique.php?action=update&id=<?= $arrayProduct['id_product'] ?>"
                          class="button is-small is-primary"
                        >
                          <!-- <span class="icon"><i class="mdi mdi-eye"></i></span> -->
                          <span class="icon"><span class="mdi mdi-pencil"></span></span>
                        </a>
                        <!-- <button
                          class="button is-small is-danger jb-modal"
                          data-target="sample-modal-<?= $arrayProduct['id_product'] ?>"
                          type="button">
                          <span class="icon"><i class="mdi mdi-trash-can"></i></span>
                        </button> -->
                      </div>
                    </td>
                  </tr>

                    <div id="sample-modal-<?= $arrayProduct['id_product'] ?>" class="modal">
                      <div class="modal-background jb-modal-close"></div>
                      <div class="modal-card">
                        <header class="modal-card-head">
                          <p class="modal-card-title">Confirmez la supression</p>
                          <button class="delete jb-modal-close" aria-label="close"></button>
                        </header>
                        <section class="modal-card-body">
                          <p>Voulez-vous réellement supprimer ce produit ?</p>
                        </section>
                        <footer class="modal-card-foot">
                          <button class="button jb-modal-close">Annuler</button>
                          <a href="?action=delete&id=<?= $arrayProduct['id_product'] ?>" class="button is-danger jb-modal-close">Supprimer</a>
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

<?php 
require_once('include/footer.php');