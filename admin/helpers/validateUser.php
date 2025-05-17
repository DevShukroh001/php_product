<?php

function validateUser($user) {
  $errors = array();

  if (!isset($user['username']) || empty($user['username'])) {
      array_push($errors, 'Username is required');
  }
  if (!isset($user['email']) || empty($user['email'])) {
      array_push($errors, 'Email is required');
  }
  if (!isset($user['password']) || empty($user['password'])) {
      array_push($errors, 'Password is required');
  }
  if (isset($user['passwordConf']) && $user['passwordConf'] !== $user['password']) {
      array_push($errors, 'Passwords do not match');
  }



$existingUser = selectOne('users', ['email' => $user['email']]);

  if ($existingUser) {
    if (isset($user['update-user']) && $existingUser['id'] != $user['id']) {
      array_push($errors, ' Email already exists');
    }

    if (isset($user['create-admin'])) { 
      array_push($errors, 'Email already exists');
    }
   
  }
  return $errors;
}
  


function validateLogin($user) {
    $errors = array();

    if (empty($user['username']) && empty($user['email'])) {
        array_push($errors, 'Username or Email is required');
    }

    if (empty($user['password'])) {
        array_push($errors, 'Password is required');
    }

    return $errors;
}
