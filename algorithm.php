<?php
/************************************************************************
 * / Class geneticAlgorithm : Genetic Algorithms
 * /
 * /************************************************************************/


require_once('individual.php');  //supporting class file
require_once('population.php');  //supporting class file

class algorithm
{

    /* GA parameters */
    public static $uniformRate = 0.5;  /* crosssover determine what where to break gene string */
    public static $mutationRate = 0.20; /* When choosing which genes to mutate what rate of random values are mutated */
    public static $poolSize = 10;  /* When selecting for crossover how large each pool should be */
    public static $max_generation_stagnant = 200;  /*how many unchanged generations before we end */
    public static $elitism = true;

    /* Public methods */

    // Convenience random function
    private static function random()
    {
        return (float)rand() / (float)getrandmax();  /* return number from 0 .. 1 as a decimal */
    }

    public static function evolvePopulation(population $pop)
    {
        $newPopulation = new population($pop->size(), false);

        // Keep our best individual
        if (algorithm::$elitism) {
            $newPopulation->saveIndividual(0, $pop->getFittest());//TODO:IMPROVE
        }

        // Crossover population
        $elitismOffset = 0;
        if (algorithm::$elitism) {
            $elitismOffset = 1;
        } else {
            $elitismOffset = 0;
        }

        // Loop over the population size and create new individuals with
        // crossover
        for ($i = $elitismOffset; $i < $pop->size(); $i += 2) {
            $indiv1 = algorithm::poolSelection($pop);
            $indiv2 = algorithm::poolSelection($pop);
            $newIndiv = algorithm::crossover($indiv1, $indiv2);
            $newPopulation->saveIndividual($i, $newIndiv[0]);
            $newPopulation->saveIndividual($i + 1, $newIndiv[1]);

        }

        // Mutate population
        for ($i = $elitismOffset; $i < $newPopulation->size(); $i++) {
            algorithm::mutate($newPopulation->getIndividual($i));
        }

        $tempPopulation = new population(2 * ($pop->size()), false);

        for ($i = 0; $i < $tempPopulation->size(); $i += 2) {
            $tempPopulation->saveIndividual($i, $pop->getIndividual($i / 2));
            $tempPopulation->saveIndividual($i + 1, $newPopulation->getIndividual($i / 2));
        }

        $tempPopulation->getFittest()->getFitness();
        $tempPopulation->sortPopulation();

        for ($i = 0; $i < $newPopulation->size(); $i++) {
            $newPopulation->saveIndividual($i, $tempPopulation->getIndividual($i));
        }

        return $newPopulation;
    }

    // Crossover individuals (aka reproduction)
    private static function crossover(individual $indiv1, individual $indiv2)
    {
        $newSol = array();
        $newSol[0] = new individual();  //create a offspring
        $newSol[1] = new individual();  //create a offspring

        // Loop through genes
        for ($i = 0; $i < $indiv1->size(); $i++) {
            // Crossover at which point 0..1 , .50 50% of time
            if (algorithm::random() <= algorithm::$uniformRate) {
                $newSol[0]->setGene($i, $indiv1->getGene($i));
                $newSol[1]->setGene($i, $indiv2->getGene($i));
            } else {
                $newSol[0]->setGene($i, $indiv2->getGene($i));
                $newSol[1]->setGene($i, $indiv1->getGene($i));
            }
        }

        return $newSol;
    }

    // Mutate an individual
    private static function mutate(individual $indiv)
    {
        // Loop through genes
        for ($i = 0; $i < $indiv->size(); $i++) {
            if (algorithm::random() <= algorithm::$mutationRate) {
                $gene = rand(0, sizeof(individual::$concreteWebService[$i]) - 1);    // Create random gene
                $indiv->setGene($i, $gene); //substitute the gene into the individual
            }
        }

    }

    // Select a pool of individuals for crossover
    private static function poolSelection(population $pop)
    {
        // Create a pool population
        $pool = new population(algorithm::$poolSize, false);

        for ($i = 0; $i < algorithm::$poolSize; $i++) {
            $randomId = rand(0, $pop->size() - 1); //Get a random individual from anywhere in the population//TODO:FUNCTION FOR RANDOM WITH INPUT TYPE OF RANDOM
            $pool->saveIndividual($i, $pop->getIndividual($randomId));

        }
        // Get the fittest
        $fittest = $pool->getFittest();
        return $fittest;
    }


}  //class
?>