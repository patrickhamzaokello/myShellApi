<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


class User
{

    private $con;
    private $idusers;
    private $fullname;
    private $email;
    private $phone;
    private $password;
    private $datecreated;


    public function __construct($con, $id)
    {
        $this->con = $con;
        $this->idusers = $id;

        $query = mysqli_query($this->con, "SELECT `idusers`, `fullname`, `email`, `phone`, `password`, `datecreated` FROM users WHERE idusers ='$this->idusers'");
        $album = mysqli_fetch_array($query);


        if (mysqli_num_rows($query) < 1) {

            $this->idusers = null;
            $this->fullname = null;
            $this->email = null;
            $this->phone = null;
            $this->password = null;
            $this->datecreated = null;

        } else {
            $this->idusers = $album['idusers'];
            $this->fullname = $album['fullname'];
            $this->email = $album['email'];
            $this->phone = $album['phone'];
            $this->password = $album['password'];
            $this->datecreated = $album['datecreated'];
        }


    }

    /**
     * @return mixed|null
     */
    public function getIdusers()
    {
        return $this->idusers;
    }

    /**
     * @return mixed|null
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * @return mixed|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed|null
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return mixed|null
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed|null
     */
    public function getDatecreated()
    {

        $php_date = strtotime($this->datecreated);
        return date('d M Y', $php_date);
    }




}