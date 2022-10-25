<?php

class MeterReadings
{

    private $con;
    private $id;
    private $reading;
    private $reading_time;
    private $shift_idshift;
    private $users_idusers;
    private $fuel_idfuel;

    public function __construct($con, $id)
    {
        $this->con = $con;
        $this->id = $id;

        $query = mysqli_query($this->con, "SELECT  `id`, `reading`, `reading_time`, `shift_idshift`, `users_idusers`, `fuel_idfuel` FROM `meter_reading` WHERE id ='$this->id'");
        $meter_reading = mysqli_fetch_array($query);


        if (mysqli_num_rows($query) < 1) {

            $this->id = null;
            $this->reading = null;
            $this->reading_time = null;
            $this->shift_idshift = null;
            $this->users_idusers = null;
            $this->fuel_idfuel = null;

        } else {
            $this->id = $meter_reading['id'];
            $this->reading = $meter_reading['reading'];
            $this->reading_time = $meter_reading['reading_time'];
            $this->shift_idshift = $meter_reading['shift_idshift'];
            $this->users_idusers = $meter_reading['users_idusers'];
            $this->fuel_idfuel = $meter_reading['fuel_idfuel'];
        }


    }

    /**
     * @return mixed|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed|null
     */
    public function getReading()
    {
        return $this->reading;
    }

    /**
     * @return mixed|null
     */
    public function getReadingTime()
    {
        return $this->reading_time;
    }

    /**
     * @return mixed|null
     */
    public function getShiftIdshift()
    {
        return new Shift($this->con, $this->shift_idshift);;
    }

    /**
     * @return mixed|null
     */
    public function getUsersIdusers()
    {
        return new User($this->con, $this->users_idusers);;
    }

    /**
     * @return mixed|null
     */
    public function getFuelIdfuel()
    {
        return new Fuel($this->con, $this->fuel_idfuel);
    }


}