<?php
abstract class User {
    abstract public function signUp($fname, $lname, $email, $password, $confirmPassword);
    abstract public function login($email, $password);
    abstract public function getProfileDetails();
    abstract public function isProfileComplete($profileDetails);
    abstract public function displayProfileDetails($Details);
    abstract public function editProfileDetails($name, $contactNumber, $email, $address);
    abstract public function displayProducts();
}
