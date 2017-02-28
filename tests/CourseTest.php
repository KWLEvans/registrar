<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

require_once "src/Course.php";
require_once "src/Student.php";

$server = 'mysql:host=localhost:8889;dbname=registrar_test';
$username = 'root';
$password = 'root';
$DB = new PDO($server, $username, $password);

class CourseTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        Course::deleteAll();
        Student::deleteAll();
    }

    function test_save()
    {
        //Arrange
        $name = "History of the American Civil War";
        $number = "HIST100";
        $test_course = new Course($name, $number);

        //Act
        $test_course->save();
        $result = Course::getAll();

        //Assert
        $this->assertEquals($test_course, $result[0]);
    }

    function test_getAll()
    {
        //Arrange
        $name = "History of the American Civil War";
        $number = "HIST100";
        $test_Course = new Course($name, $number);
        $test_Course->save();

        $name = "Chemistry of Glassblowing";
        $number = "CHEM3000";
        $test_Course2 = new Course($name, $number);
        $test_Course2->save();

        //Act
        $result = Course::getAll();

        //Assert
        $this->assertEquals([$test_Course, $test_Course2], $result);
    }

    function test_addStudents()
    {
        //Arrange
        $name = "Keith";
        $enrollment_date = "2013-12-23";
        $test_student = new Student($name, $enrollment_date);
        $test_student->save();

        $name = "Symphonic Studies";
        $number = "ORCH20000";
        $test_course = new Course($name, $number);
        $test_course->save();

        //Act
        $test_course->addStudent($test_student->getId());
        $result = $test_course->getStudents();

        //Assert
        $this->assertEquals($test_student, $result[0]);
    }
}

?>
