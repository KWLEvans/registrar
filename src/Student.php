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
            $courses = [];
            
            foreach ($returned_courses as $course) {
                $name = $course['name'];
                $number = $course['number'];
                $id = $course['id'];
                $new_course = new Course($name, $number, $id);
                array_push($courses, $new_course);
            }
            return $courses;
        }

        function save()
        {
            $exec = $GLOBALS['DB']->prepare("INSERT INTO students (name, enrollment_date) VALUES (:name, :enrollment_date);");
            $exec->execute([':name' => $this->getName(), ':enrollment_date' => $this->getEnrollmentDate()]);
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $students = [];
            $returned_students = $GLOBALS['DB']->query("SELECT * FROM students;");
            foreach ($returned_students as $student) {
                $name = $student['name'];
                $enrollment_date = $student['enrollment_date'];
                $id = $student['id'];
                $new_student = new Student($name, $enrollment_date, $id);
                array_push($students, $new_student);
            }
            return $students;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM students;DELETE FROM students_courses;");
        }
    }

?>
