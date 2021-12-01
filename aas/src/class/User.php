<!-- getter setter for User class -->

<?php
include_once "database.php";

class User
{
    // Properties
    public $user_id;
    public $email;
    public $first_name;
    public $last_name;
    public $password;
    public $dob;
    public $contact_no;
    public $role;
    public $profile_pic;
    public $username;

    // Methods
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    public function getUserID()
    {
        return $this->user_id;
    }

    public function setUserID($user_id)
    {
        $this->user_id = $user_id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
    }

    public function getLastName()
    {
        return $this->last_name;
    }

    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getDob()
    {
        return $this->dob;
    }

    public function setDob($dob)
    {
        $this->dob = $dob;
    }

    public function getContactNo()
    {
        return $this->contact_no;
    }

    public function setContactNo($contact_no)
    {
        $this->contact_no = $contact_no;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    public function getProfilePic()
    {
        return $this->profile_pic;
    }

    public function setProfilePic($profile_pic)
    {
        $this->profile_pic = $profile_pic;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }
}