<?php

namespace Core\Authentification;

class User
{

    private Int $id;
    private String $pseudo;
    private String $email;

    /**
     * __construct
     *
     * @param  Int $id
     * @param  String $first_name
     * @param  String $last_name
     * @param  String $email
     * @param  Bool $status
     * @param  String $avatar
     * @return void
     */
    public function __construct(Int $id, String $pseudo, String $email)
    {

        $this->id = $id;
        $this->pseudo = $pseudo;
        $this->email = $email;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of first_name
     */
    public function getFirst_name()
    {
        return $this->first_name;
    }

    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get the value of status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get the value of avatar
     */
    public function getAvatar()
    {
        return $this->avatar;
    }
}
