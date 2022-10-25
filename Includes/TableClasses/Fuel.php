<?php

class Fuel
{
    private $con;
    private $idfuel;
    private $name;
    private $dateCreated;


    public function __construct($con, $id)
    {
        $this->con = $con;
        $this->idfuel = $id;

        $query = mysqli_query($this->con, "SELECT `idfuel`, `name`, `dateCreated` FROM `fuel` WHERE idshift ='$this->idfuel'");
        $fuel = mysqli_fetch_array($query);


        if (mysqli_num_rows($query) < 1) {

            $this->idfuel = null;
            $this->name = null;
            $this->dateCreated = null;

        } else {
            $this->idfuel = $fuel['idfuel'];
            $this->name = $fuel['name'];
            $this->dateCreated = $fuel['dateCreated'];
        }


    }

    /**
     * @return mixed|null
     */
    public function getIdfuel()
    {
        return $this->idfuel;
    }

    /**
     * @return mixed|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed|null
     */
    public function getDateCreated()
    {
        $php_date = strtotime($this->dateCreated);
        return date('d M Y', $php_date);
    }




}