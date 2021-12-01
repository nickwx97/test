<?php 
require('srp.php');

function registerSRP($username, $password) {
    $srp = new srp();

    $smallSalt = $srp->getRandomSeed();
    $combineUserPass = $srp->generateX($smallSalt, $username, $password);
    $passwordVerifier = $srp->generateV($combineUserPass);

    return array($passwordVerifier, $smallSalt);
}
?>