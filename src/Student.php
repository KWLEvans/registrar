<?php

    class Student
    {
        private $name;
        private $enrollment_date;
        private $id;

        function __construct($name, $enrollment_date, $id = null)
        {
            $this->name = $name;
            $this->enrollment_date = $enrollment_date;
            $this->id = $id;
        }

        function getName()
        {
            return $this->name;
        }

        function setName($new_name)
        {
            $this->name = $new_name;
        }

        function getEnrollmentDate()
        {
            return $this->enrollment_date;
        }

        function setEnrollmentDate($new_enrollment_date)
        {
            $this->enrollment_date = $new_enrollment_date;
        }

        function getId()
        {
            return $this->id;
        }

        function addCourse($course_id)
        {
            $GLOBALS['DB']->exec("INSERT INTO students_courses (student_id, course_id) VALUES ({$this->getId()}, {$course_id});");
        }

        function getCourses()
        {
            $returned_courses = $GLOBALS['DB']->query("SELECT courses.* FROM students
                JOIN students_courses ON (students_courses.student_id = students.id)
                JOIN courses ON (courses.id = students_courses.course_id)
                WHERE students.id = {$this->getId()};");
            $courses = $returned_courses->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Course', ['name', 'number', 'id']);
            return $courses;
        }

        function updateName($new_name)
        {
            $this->setName($new_name);
            $exec = $GLOBALS['DB']->prepare("UPDATE students SET name = :name WHERE id = :id;");
            $exec->execute([':name' => $this->getName(), ':id' => $this->getId()]);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM students WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM students_courses WHERE student_id = {$this->getId()};");
        }

        function save()
        {
            $exec = $GLOBALS['DB']->prepare("INSERT INTO students (name, enrollment_date) VALUES (:name, :enrollment_date);");
            $exec->execute([':name' => $this->getName(), ':enrollment_date' => $this->getEnrollmentDate()]);
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_students = $GLOBALS['DB']->query("SELECT * FROM students;");
            $students = $returned_students->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Student', ['name', 'enrollment_date', 'id']);
            return $students;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM students;DELETE FROM students_courses;");
        }
    }

?>
