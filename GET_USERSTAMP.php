<?php
use google\appengine\api\users\User;
use google\appengine\api\users\UserService;

$user = UserService::getCurrentUser();

$UserStamp=$user->getEmail();
$UserStamp=strtolower($UserStamp);

?>