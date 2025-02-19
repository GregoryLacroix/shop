<?php 
require_once('../include/init.php');
$_SESSION['msg'] = false;

// Si l'utilisateur n'est pas connecté ou est connecté mais non admin, on le redirige vers la page index.php

if(!adminConnected()){
  // header('location: ' . URL . ' index.php');
  //                  http://localhost/PHP/shop/index.php
  header('location: ' . URL . 'index.php');
}

// ---- USER 
$data = $connect_db->query("SELECT id_user, firstName, lastName, email, DATE_FORMAT(createdAt, '%d/%m/%Y') AS dateFr, roles FROM user WHERE roles = 'user'");
$users = $data->fetchAll(PDO::FETCH_ASSOC);
// echo '<pre>'; print_r($users); echo '</pre>';

$nbUsers = $data->rowCount();

if($nbUsers <= 1)
  $txtNbUsers = "$nbUsers membre";
else 
  $txtNbUsers = "$nbUsers membres";


// ---- ADMIN
$data = $connect_db->query("SELECT id_user, firstName, lastName, email, DATE_FORMAT(createdAt, '%d/%m/%Y') AS dateFr, roles FROM user WHERE roles = 'admin'");
$admins = $data->fetchAll(PDO::FETCH_ASSOC);
// echo '<pre>'; print_r($admins); echo '</pre>';

$nbAdmins = $data->rowCount();

if($nbAdmins <= 1)
  $txtNbAdmins = "$nbAdmins administrateur";
else 
  $txtNbAdmins = "$nbAdmins administrateurs";

// UPDATE ROLES
if(isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] === 'POST'){
  echo '<pre>'; print_r($_POST); echo '</pre>';
  $data = $connect_db->prepare("UPDATE `user` SET roles = :roles WHERE id_user = :id");
  $data->bindValue(':roles', $_POST['roles'], PDO::PARAM_STR);
  $data->bindValue(':id', $_POST['id_user'], PDO::PARAM_STR);
  $data->execute();

  $_SESSION['msgValidation'] = "Le role utilisateur a été modifié.";

  $_SESSION['msg'] = true;

  header('location: gestion_user.php');
}

require_once('include/header.php');
?>
    <section class="section is-title-bar">
      <div class="level">
        <div class="level-left">
          <div class="level-item">
            <ul>
              <li>Admin</li>
              <li>Utilisateurs</li>
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
            <?= $txtNbUsers ?>
          </p>
          <a href="#" class="card-header-icon">
            <span class="icon"><i class="mdi mdi-reload"></i></span>
          </a>
        </header>
        <div class="card-content">
          <div class="b-table has-pagination">
            <div class="table-wrapper has-mobile-cards">
              <table
                class="table is-fullwidth is-striped is-hoverable is-fullwidth"  id="table-users">
                <thead>
                  <tr>
                    <th class="is-checkbox-cell">
                      <label class="b-checkbox checkbox">
                        <input type="checkbox" value="false" />
                        <span class="check"></span>
                      </label>
                    </th>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Date</th>
                    <th>Role</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($users as $key => $item): ?>
                  <tr>
                    <td class="is-checkbox-cell">
                      <label class="b-checkbox checkbox">
                        <input type="checkbox" value="false" />
                        <span class="check"></span>
                      </label>
                    </td>
                    <td data-label="Prénom"><?= $item['firstName'] ?></td>
                    <td data-label="Nom"><?= $item['lastName'] ?></td>
                    <td data-label="Email"><?= $item['email'] ?></td>
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
                                <input type="hidden" name="id_user" value="<?= $item['id_user'] ?>">
                                <select name="roles" class="formRoles">
                                  <option value="user" <?php if(isset($item['roles']) && $item['roles'] == "user") echo 'selected'; ?>>ROLE USER</option>
                                  <option value="admin" <?php if(isset($item['roles']) && $item['roles'] == "admin") echo 'selected'; ?>>ROLE ADMIN</option>
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
                        <!-- <a
                          href="?action=details&id=<?= $item['id_user'] ?>"
                          class="button is-small is-primary"
                        >
                          <span class="icon"><i class="mdi mdi-eye"></i></span>
                        </a> -->
                        <button
                          class="button is-small is-danger jb-modal"
                          data-target="sample-modal-<?= $item['id_user'] ?>"
                          type="button">
                          <span class="icon"><i class="mdi mdi-trash-can"></i></span>
                        </button>
                      </div>
                    </td>
                  </tr>

                  <div id="sample-modal-<?= $item['id_user'] ?>" class="modal">
                      <div class="modal-background jb-modal-close"></div>
                        <div class="modal-card">
                          <header class="modal-card-head">
                            <p class="modal-card-title">Confirmez la supression</p>
                            <button class="delete jb-modal-close" aria-label="close"></button>
                          </header>
                          <section class="modal-card-body">
                            <p>Voulez-vous réellement supprimer cet utilisateur ?</p>
                          </section>
                          <footer class="modal-card-foot">
                            <button class="button jb-modal-close">Annuler</button>
                            <a href="?action=delete&id=<?= $item['id_user'] ?>" class="button is-danger jb-modal-close">Supprimer</a>
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

     <section class="section is-main-section">
      <div class="card has-table">
        <header class="card-header">
          <p class="card-header-title">
            <span class="icon"><span class="mdi mdi-cart-outline"></span>
            </span>
            <?= $txtNbAdmins ?>
          </p>
          <a href="#" class="card-header-icon">
            <span class="icon"><i class="mdi mdi-reload"></i></span>
          </a>
        </header>
        <div class="card-content">
          <div class="b-table has-pagination">
            <div class="table-wrapper has-mobile-cards">
              <table
                class="table is-fullwidth is-striped is-hoverable is-fullwidth" id="table-admins">
                <thead>
                  <tr>
                    <th class="is-checkbox-cell">
                      <label class="b-checkbox checkbox">
                        <input type="checkbox" value="false" />
                        <span class="check"></span>
                      </label>
                    </th>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Date</th>
                    <th>Role</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($admins as $key => $item): ?>
                  <tr>
                    <td class="is-checkbox-cell">
                      <label class="b-checkbox checkbox">
                        <input type="checkbox" value="false" />
                        <span class="check"></span>
                      </label>
                    </td>
                    <td data-label="Prénom"><?= $item['firstName'] ?></td>
                    <td data-label="Nom"><?= $item['lastName'] ?></td>
                    <td data-label="Email"><?= $item['email'] ?></td>
                    <td data-label="Date">
                      <small
                        class="has-text-grey is-abbr-like"
                        title="<?= $item['dateFr'] ?>"><?= $item['dateFr'] ?></small>
                    </td>
                    <td data-label="Roles">
                      <form action="" method="post">
                        <div class="field-body">
                          <div class="field is-narrow">
                            <div class="control">
                              <div class="select is-fullwidth">
                                <input type="hidden" name="id_user" value="<?= $item['id_user'] ?>">
                                <select name="roles" class="formRoles">
                                  <option value="user" <?php if(isset($item['roles']) && $item['roles'] == "user") echo 'selected'; ?>>ROLE USER</option>
                                  <option value="admin" <?php if(isset($item['roles']) && $item['roles'] == "admin") echo 'selected'; ?>>ROLE ADMIN</option>
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
                        <!-- <a
                          href="?action=details&id=<?= $item['id_user'] ?>"
                          class="button is-small is-primary"
                        >
                          <span class="icon"><i class="mdi mdi-eye"></i></span>
                        </a> -->
                        <button
                          class="button is-small is-danger jb-modal"
                          data-target="sample-modal-<?= $item['id_user'] ?>"
                          type="button">
                          <span class="icon"><i class="mdi mdi-trash-can"></i></span>
                        </button>
                      </div>
                    </td>
                  </tr>

                  <div id="sample-modal-<?= $item['id_user'] ?>" class="modal">
                      <div class="modal-background jb-modal-close"></div>
                        <div class="modal-card">
                          <header class="modal-card-head">
                            <p class="modal-card-title">Confirmez la supression</p>
                            <button class="delete jb-modal-close" aria-label="close"></button>
                          </header>
                          <section class="modal-card-body">
                            <p>Voulez-vous réellement supprimer cet administrateur ?</p>
                          </section>
                          <footer class="modal-card-foot">
                            <button class="button jb-modal-close">Annuler</button>
                            <a href="?action=delete&id=<?= $item['id_user'] ?>" class="button is-danger jb-modal-close">Supprimer</a>
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
if($_SESSION['msg'] == false){
  unset($_SESSION['msgValidation']);
}