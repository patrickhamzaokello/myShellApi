<?php

class Shift
{
    private $con;
    private $idshift;
    private $name;
    private $time;
    private $datecreated;


    public function __construct($con, $id)
    {
        $this->con = $con;
        $this->idshift = $id;

        $query = mysqli_query($this->con, "SELECT `idshift`, `name`, `time`, `datecreated` FROM `shift` WHERE idshift ='$this->idshift'");
        $shift = mysqli_fetch_array($query);


        if (mysqli_num_rows($query) < 1) {

            $this->idshift = null;
            $this->name = null;
            $this->time = null;
            $this->datecreated = null;

        } else {
            $this->idshift = $shift['idshift'];
            $this->name = $shift['name'];
            $this->time = $shift['time'];
            $this->datecreated = $shift['datecreated'];
        }


    }

    /**
     * @return mixed|null
     */
    public function getIdshift()
    {
        return $this->idshift;
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
    public function getTime()
    {
        return $this->time;
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