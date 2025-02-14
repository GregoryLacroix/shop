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