<?php
/**
 * Created by PhpStorm.
 * User: zeenomlabs
 * Date: 3/13/2016
 * Time: 7:55 AM
 */

namespace App\models\helperClasses;


class VehiclePosition {
    private $vehicle_number;
    private $capacity = 0;
    private $income = 0;
    private $trips = 0;
    private $all_other_expenses_including_trips = 0;
    private $trips_expense = 0;
    private $repair_maintainence = 0;
    private $installment = 0;
    private $short_dip = 0;
    private $shortage_amount = 0;
    private $salary = 0;
    private $total_fuel = 0;
    private $total_km = 0;

    public function __construct($vehicle, $data){
        $this->setVehicleNumber($vehicle);
        if($data['meter'] != null) {
            $this->setCapacity($data['meter']->capacity);
            $this->setTrips($data['meter']->num_of_trips);
            $this->setTotalFuel($data['meter']->total_fuel);
            $this->setTotalKm($data['meter']->total_kilometers);
        }
        if($data['income'] != null){
            $this->setIncome($data['income']->total_income);
        }
        if($data['installment'] != null){
            $this->setInstallment($data['installment']);
        }
        if($data['shortage'] != null){
            $this->setShortageAmount($data['shortage']->shortage_amount);
            $this->setShortDip($data['shortage']->shortage_dip);
        }
        if($data['salary'] != null){
            $this->setSalary($data['salary']);
        }
        if($data['repair_maintainence'] != null){
            $this->setRepairMaintainence($data['repair_maintainence']);
        }
        if($data['trip_expenses'] != null){
            $this->setTripExpenses($data['trip_expenses']);
        }
        if($data['all_other_expense_including_tips'] != null){
            $this->setAllOtherExpenseIncludingTrips($data['all_other_expense_including_tips']);
        }
    }

    /**
     * @return mixed
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * @param mixed $capacity
     */
    public function setCapacity($capacity)
    {
        $this->capacity = round($capacity, 2);
    }

    /**
     * @return mixed
     */
    public function getTripExpenses()
    {
        return $this->trips_expense;
    }

    /**
     * @param mixed $expenses
     */
    public function setTripExpenses($expenses)
    {
        $this->trips_expense = round($expenses, 2);
    }

    /**
     * @return mixed
     */
    public function getAllOtherExpenseIncludingTrips()
    {
        return $this->all_other_expenses_including_trips;
    }

    /**
     * @param mixed $expenses
     */
    public function setAllOtherExpenseIncludingTrips($expenses)
    {
        $this->all_other_expenses_including_trips = round($expenses, 2);
    }

    /**
     * @return mixed
     */
    public function getOtherExpenses()
    {
        return $this->getAllOtherExpenseIncludingTrips()-$this->getTripExpenses();
    }

    /**
     * @return mixed
     */
    public function getIncome()
    {
        return $this->income;
    }

    /**
     * @param mixed $income
     */
    public function setIncome($income)
    {
        $this->income = round($income, 2);
    }

    /**
     * @return mixed
     */
    public function getInstallment()
    {
        return $this->installment;
    }

    /**
     * @param mixed $installment
     */
    public function setInstallment($installment)
    {
        $this->installment = round($installment, 2);
    }

    /**
     * @return mixed
     */
    public function getRepairMaintainence()
    {
        return $this->repair_maintainence;
    }

    /**
     * @param mixed $repair_maintainence
     */
    public function setRepairMaintainence($repair_maintainence)
    {
        $this->repair_maintainence = round($repair_maintainence, 2);
    }

    /**
     * @return mixed
     */
    public function getSalary()
    {
        return $this->salary;
    }

    /**
     * @param mixed $salary
     */
    public function setSalary($salary)
    {
        $this->salary = round($salary, 2);
    }

    /**
     * @return mixed
     */
    public function getShortDip()
    {
        return $this->short_dip;
    }

    /**
     * @param mixed $short_dip
     */
    public function setShortDip($short_dip)
    {
        $this->short_dip = round($short_dip, 2);
    }

    /**
     * @return mixed
     */
    public function getShortageAmount()
    {
        return $this->shortage_amount;
    }

    /**
     * @param mixed $shortage_amount
     */
    public function setShortageAmount($shortage_amount)
    {
        $this->shortage_amount = round($shortage_amount, 2);
    }

    /**
     * @return mixed
     */
    public function getTotalFuel()
    {
        return $this->total_fuel;
    }

    /**
     * @param mixed $total_fuel
     */
    public function setTotalFuel($total_fuel)
    {
        $this->total_fuel = round($total_fuel, 2);
    }

    /**
     * @return mixed
     */
    public function getTotalKm()
    {
        return $this->total_km;
    }

    /**
     * @param mixed $total_km
     */
    public function setTotalKm($total_km)
    {
        $this->total_km = round($total_km, 2);
    }

    /**
     * @return mixed
     */
    public function getTrips()
    {
        return $this->trips;
    }

    /**
     * @param mixed $trips
     */
    public function setTrips($trips)
    {
        $this->trips = $trips;
    }

    /**
     * @return mixed
     */
    public function getVehicleNumber()
    {
        return $this->vehicle_number;
    }

    /**
     * @param mixed $vehicle_number
     */
    public function setVehicleNumber($vehicle_number)
    {
        $this->vehicle_number = $vehicle_number;
    }

    public function getAvgFuel()
    {
        if($this->getTotalKm() > 0)
            return round($this->getTotalKm()/$this->getTotalFuel(), 2);
        return 0;
    }

} 