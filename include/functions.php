<?php 
// ------ FONCTION UTILISATEUR AUTHENTIFIE
// Fontion permettant de savoir si l'utilisateur est authentifié sur le site
function userConnected(){
    // Si l'indice 'user' dans le fichier de session n'est pas définit, cela veut dire que l'internaute n'est pas passé par la page connexion et n'est pas authentifié
    if(isset($_SESSION['user']))
        return true;
    else
        return false; // on retourne true si l'indice 'user' est définit dans la session
}

// ------ FONCTION ADMINISTRATEUR AUTHENTIFIE
// Fonction permettant de savoir si un administrateur est authentifié sur le site

function adminConnected(){
    // Si à l'indice 'roles' dans la session, la valeur est admin, cela veut dire que dire que c'est un administrateur on retourne true

    if(userConnected() && $_SESSION['user']['roles'] == 'admin') 
        return true;
    else
        return false; // on retourne false si dans la session le roles n'est pas 'admin'
}

/*
    cart => [
        id_product => [
            0 => 15,
            1 => 7
        ]

        title => [
            0 => Chemise bleu,
            1 => Pull vert
        ]
    ]
*/

// ------- FONCTION CREATION PANIER SESSION
function createCart(){
    // Si l'indice 'cart' n'est pas définit dans la session de l'utilisateur, cela veut dire que l'intilisateur n'a ajouté aucun produit dans le panier, alors on crée les différents tableaux dans la session
    if(!isset($_SESSION['cart'])){
        $_SESSION['cart'] = [];
        $_SESSION['cart']['id_product'] = [];
        $_SESSION['cart']['title'] = [];
        $_SESSION['cart']['picture'] = [];
        $_SESSION['cart']['reference'] = [];
        $_SESSION['cart']['quantity'] = [];
        $_SESSION['cart']['price'] = [];
    }
}

// ------- FONCTION AJOUTER PRODUIT DANS PANIER SESSION
function addProductToCart($id_product, $title, $picture, $reference, $quantity, $price){
    createCart(); // On contrôle si le panier existe ou non dans la session

    // On contrôle si l'id du produit que l'on tente d'ajouter dans le session panier existe déjà
    $positionProduct = array_search($id_product, $_SESSION['cart']['id_product']);
    // var_dump($positionProduct);

    // Si la valeur de $positionProduct est différente de false, cela veut dire que l'id_product existe dans le panier, on modifie seulement la quantité du produit
    if($positionProduct !== false){
        $_SESSION['cart']['quantity'][$positionProduct] += $quantity;
    }else{
        // Sinon l'id n'est pas dans le session, on crée une nouvelle ligne dans le panier
        // les [] vide permettent de créer des indices numérique dans les tableaux Array
        $_SESSION['cart']['id_product'][] = $id_product;
        $_SESSION['cart']['title'][] = $title;
        $_SESSION['cart']['picture'][] = $picture;
        $_SESSION['cart']['reference'][] = $reference;
        $_SESSION['cart']['quantity'][] = $quantity;
        $_SESSION['cart']['price'][] = $price;
    }
}

// ------- FONCTION SUPPRESSION ARTICLE PANIER
//                              7
function removeProductToCart($id_product){

    // On cherche à quel indice se trouve l'id du produit a supprimé dans la session en passant par le tableau Array $_SESSION['cart']['id_product']
    //                                  7
    $positionProduct = array_search($id_product, $_SESSION['cart']['id_product']);
    // var_dump($positionProduct);

    // Si $positionProduct est différent de false, cela veut dire que array_search a retourné l'indice du produit
    if($positionProduct !== false){
        // La fonction prédéfinie array_splice permet de supprimer un élément dans un array à un indice correspondant et elle remonte les indices inférieur vers les indices supérieurs, si je supprime le produit à l'indice [2] du tableau Array, le produit à l'indice [3] remonte à l'indice [2]
        //                                                  [1]
        array_splice($_SESSION['cart']['id_product'], $positionProduct, 1);
        array_splice($_SESSION['cart']['title'], $positionProduct, 1);
        array_splice($_SESSION['cart']['picture'], $positionProduct, 1);
        array_splice($_SESSION['cart']['reference'], $positionProduct, 1);
        array_splice($_SESSION['cart']['quantity'], $positionProduct, 1);
        array_splice($_SESSION['cart']['price'], $positionProduct, 1);
    }
}

// ------- FONCTION CALCUL MONTANT TOTAL DU PANIER
function totalAmount(){
    $total = 0;
    for($i = 0; $i < count($_SESSION['cart']['id_product']); $i++){ 
        $total += $_SESSION['cart']['quantity'][$i] * $_SESSION['cart']['price'][$i];
    }
    return round($total, 2);
}

// ------- FONCTION LIENS ACTIFS NAV
//                /PHP/shop/product.php
function activeLink($url){
    if($_SERVER['PHP_SELF'] == $url)
        echo ' active';
}
