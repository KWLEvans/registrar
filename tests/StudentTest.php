<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Student.php";

    $server = 'mysql:host=localhost:8889;dbname=registrar_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class StudentTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Student::deleteAll();
        }

        function test_save()
        {
            //Arrange
            $name = "Tennyson Wunderbar";
            $date = "2013-12-23";
            $test_student = new Student($name, $date);

            //Act
            $test_student->save();
            $result = Student::getAll();
            
            //Assert
            $this->assertEquals($test_student, $result[0]);
        }

        function test_getAll()
        {
            //Arrange
            $name = "Tennyson Wunderbar";
            $date = "2013-12-23";
            $test_Student = new Student($name, $date);
            $test_Student->save();

            $name = "Sandra Handerson";
            $date = "2016-02-12";
            $test_Student2 = new Student($name, $date);
            $test_Student2->save();

            //Act
            $result = Student::getAll();

            //Assert
            $this->assertEquals([$test_Student, $test_Student2], $result);
        }
    }

?>
