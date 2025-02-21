<?php 
require_once('include/init.php');

// Si l'indice '?id=' est définit dans l'URL 
if(isset($_GET['id'])){
  $data = $connect_db->prepare("SELECT * FROM product WHERE id_product = :id");
  $data->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
  $data->execute();

  // Si la requete ne retourne aucun résultat, on redirige l'utilisateur vers la page index.php
  if(!$data->rowCount()){
    header('location: product.php');
  }

  $product = $data->fetch(PDO::FETCH_ASSOC);
}else {
  // Sinon on redirige l'internaute
  header('location: product.php');
}

//------- TESTIMONIAL SELECT BDD
$data = $connect_db->query("SELECT * FROM testimonial WHERE product_id = $product[id_product] ORDER BY date DESC");
$testimonials = $data->fetchAll(PDO::FETCH_ASSOC);
// echo '<pre>'; print_r($testimonials); echo '</pre>';

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
        <h2>Avis<span> clients</span></h2>
      </div>

      <div class="row mt-5">
        <?php 
        foreach($testimonials as $key => $item): 
        $data = $connect_db->query("SELECT CONCAT_WS(' ', firstName, lastName) AS userName FROM user WHERE id_user = $item[user_id]");
        $user = $data->fetch(PDO::FETCH_ASSOC);  

        $date = new DateTimeImmutable($item['date']);
        $dateFr = $date->format('d/m/Y');
        // echo '<pre>'; print_r($user); echo '</pre>';
        ?>
        <div class="col-sm-12 col-md-12 col-lg-12">
          <div class="full">
            <div class="rating">
              <small class="testimonial__poster">Posté par <?= $user['userName'] ?> le <?= $dateFr ?></small>

              <input type="radio" name="rating-<?= $item['id_testimonial'] ?>" value="5" id="rating-5-<?= $item['id_testimonial'] ?>" <?php if(isset($item['rating']) && $item['rating'] == 5) echo 'checked' ?>/>

              <label for="rating-5-<?= $item['id_testimonial'] ?>"></label>
              <input type="radio" name="rating-<?= $item['id_testimonial'] ?>" value="4" id="rating-4-<?= $item['id_testimonial'] ?>" <?php if(isset($item['rating']) && $item['rating'] == 4) echo 'checked' ?> />

              <label for="rating-4-<?= $item['id_testimonial'] ?>"></label>
              <input type="radio" name="rating-<?= $item['id_testimonial'] ?>" value="3" id="rating-3-<?= $item['id_testimonial'] ?>" <?php if(isset($item['rating']) && $item['rating'] == 3) echo 'checked' ?>/>

              <label for="rating-3-<?= $item['id_testimonial'] ?>"></label>
              <input type="radio" name="rating-<?= $item['id_testimonial'] ?>" value="2" id="rating-2-<?= $item['id_testimonial'] ?>" <?php if(isset($item['rating']) && $item['rating'] == 2) echo 'checked' ?>/>

              <label for="rating-2-<?= $item['id_testimonial'] ?>"></label>
              <input type="radio" name="rating-<?= $item['id_testimonial'] ?>" value="1" id="rating-1-<?= $item['id_testimonial'] ?>" <?php if(isset($item['rating']) && $item['rating'] == 1) echo 'checked' ?> />
              <label for="rating-1"></label>
            </div>
          </div>
          <p><?= $item['message'] ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <!-- end product section -->
  <!-- footer section -->
   
<?php 
require_once('include/footer.php');
if($_SESSION['msg'] == false){
  unset($_SESSION['msgValidateTestimonial']);
}
?>