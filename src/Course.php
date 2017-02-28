<?php

class Course
{
    private $name;
    private $number;
    private $id;

    function __construct ($name, $number, $id = null)
    {
        $this->name = $name;
        $this->number = $number;
        $this->id = $id;
    }

    function getName()
    {
        return $this->name;
    }

    function setName($new_name)
    {
        $this->$name = $new_name;
    }

    function getNumber()
    {
        return $this->number;
    }

    function setNumber($new_number)
    {
        $this->$number = $new_number;
    }

    function save()
    {
        $exec = $GLOBALS['DB']->prepare("INSERT INTO courses (name, number) VALUES (:name, :number);");
        $exec->execute([':name' => $this->getName(), ':number' => $this->getNumber()]);
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    static function getAll()
    {
        $courses = [];
        $returned_courses = $GLOBALS['DB']->query("SELECT * FROM courses;");
        foreach ($returned_courses as $course) {
            $name = $course['name'];
            $number = $course['number'];
            $id = $course['id'];
            $new_course = new Course($name, $number, $id);
            array_push($courses, $new_course);
        }
        return $courses;
    }

    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM courses;");
    }
}


?>
