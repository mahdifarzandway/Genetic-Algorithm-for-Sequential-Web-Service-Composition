<?php
/************************************************************************
 * / Class fitnesscalc : Genetic Algorithms
 * /
 * /************************************************************************/

require_once('individual.php');  //supporting class file

class fitnesscalc
{

    public static $solution = array();  //empty array of arbitrary length
    public static $numberAbstractWebService = -1;
    /* Public methods */
    // Set a candidate solution as a byte array

    // To make it easier we can use this method to set our candidate solution with string of 0s and 1s
    static function setSolution($newSolution)
    {
        // Loop through each character of our string and save it in our string  array
        fitnesscalc::$solution = str_split($newSolution);
    }

    static function setNumberAbstractWebService($numberAbstractWebService)
    {
        fitnesscalc::$numberAbstractWebService = $numberAbstractWebService;
    }

    static function getNumberAbstractWebService()
    {
        return fitnesscalc::$numberAbstractWebService;
    }

    // Calculate individuals fitness by comparing it to our candidate solution
    // low fitness values are better,0=goal fitness is really a cost function in this instance
    static function getFitness($individual)
    {
        $fitness = 0;

        for ($i = 0; $i < $individual->size(); $i++) {
            $fitness += individual::$concreteWebService[$i][$individual->getGene($i)];
        }

        return $fitness;  //inverse of cost function

    }

    // Get optimum fitness
    static function getMaxFitness($maxFitness)
    {
        return $maxFitness;
    }


}  //end class


?>