<?php

class SimpleTestListener implements PHPUnit_Framework_TestListener
{
    public function addError(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
    // printf("Error while running test '%s'.\n", $test->getName());
    }
 
    public function addFailure(PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time)
    {
    	
    	// print_r($test);
    	
        printf("\n\n >>> Test '%s' failed.\n", $test->getName());
    }
 
    public function addIncompleteTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
       // printf("Test '%s' is incomplete.\n", $test->getName());
    }
 
    public function addSkippedTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
       // printf("Test '%s' has been skipped.\n", $test->getName());
    }
 
    public function startTest(PHPUnit_Framework_Test $test)
    {
        // printf("Test '%s' started.\n", $test->getName());
    }
 
    public function endTest(PHPUnit_Framework_Test $test, $time)
    {
        // printf("\n >>> Test '%s' ended.\n", $test->getName());
    }
 
    public function startTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
    	
    	$name = str_replace("_",".", strtolower($suite->getName()) );
        echo "\n";
        echo str_repeat('-', 180);
    	echo "\n>>> startTestSuite: " . $name;
		
		//echo 'starting test suite. First, clearing all test statistics';
		
        //printf("TestSuite '%s' started.\n", $suite->getName());
    }
 
    public function endTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
//        printf("TestSuite '%s' ended.\n", $suite->getName());
		echo "\n".'ending test suite. check if the stats are OK.';
    }
    

}
