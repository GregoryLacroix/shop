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
    
    // les [] vide permettent de créer des indices numérique dans les tableaux Array
    $_SESSION['cart']['id_product'][] = $id_product;
    $_SESSION['cart']['title'][] = $title;
    $_SESSION['cart']['picture'][] = $picture;
    $_SESSION['cart']['reference'][] = $reference;
    $_SESSION['cart']['quantity'][] = $quantity;
    $_SESSION['cart']['price'][] = $price;
}