<?php
/*
Expression régulière de mot de passe fort
L'expression régulière ci-dessous vérifie qu'un mot de passe :

Contient au moins 8 caractères de longueur. Ajustez-le en modifiant {8,}
Au moins une lettre majuscule anglaise. Vous pouvez supprimer cette condition en supprimant (?=.*?[AZ])
Au moins une lettre minuscule anglaise. Vous pouvez supprimer cette condition en supprimant (?=.*?[az])
Au moins un chiffre. Vous pouvez supprimer cette condition en supprimant (?=.*?[0-9])
Au moins un caractère spécial, vous pouvez supprimer cette condition en supprimant (?=.*?[#?!@$%^&*-])
*/

$password_regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/"; 
echo preg_match($password_regex, 'secret'); // returns 0
echo preg_match($password_regex, '-Secr3t.'); // returns 1