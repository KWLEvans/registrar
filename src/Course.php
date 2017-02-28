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

    function getId()
    {
        return $this->id;
    }

    function addStudent($student_id)
    {
        $GLOBALS['DB']->exec("INSERT INTO students_courses (student_id, course_id) VALUES ({$student_id}, {$this->getId()});");
    }

    function getStudents()
    {
        $returned_students = $GLOBALS['DB']->query("SELECT students.* FROM courses
            JOIN students_courses ON (students_courses.course_id = courses.id)
            JOIN students ON (students.id = students_courses.student_id)
            WHERE courses.id = {$this->getId()};");
        $students = $returned_students->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Student', ['name', 'enrollment_date', 'id']);
        return $students;
    }

    function updateName($new_name)
    {
        $this->setName($new_name);
        $exec = $GLOBALS['DB']->prepare("UPDATE courses SET name = :name WHERE id = :id");
        $exec->execute([':name' => $this->getName(), ':id' => $this->getId()]);
    }

    function delete()
    {
        $GLOBALS['DB']->exec("DELETE FROM courses where id = {$this->getId()};");
        $GLOBALS['DB']->exec("DELETE FROM students_courses where course_id = {$this->getId()};");
    }

    function save()
    {
        $exec = $GLOBALS['DB']->prepare("INSERT INTO courses (name, number) VALUES (:name, :number);");
        $exec->execute([':name' => $this->getName(), ':number' => $this->getNumber()]);
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    static function find($id)
    {
        $found_course = $GLOBALS['DB']->query("SELECT * FROM courses WHERE id = {$id};");
        $course = $found_course->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Course', ['name', 'number', 'id']);
        return $course[0];
    }

    static function getAll()
    {
        $returned_courses = $GLOBALS['DB']->query("SELECT * FROM courses;");
        $courses = $returned_courses->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Course', ['name', 'number', 'id']);
        return $courses;
    }

    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM courses;DELETE FROM students_courses;");
    }
}


?>
