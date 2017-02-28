<?php

    date_default_timezone_set("America/Los_Angeles");
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Student.php";
    require_once __DIR__."/../src/Course.php";

    $app = new Silex\Application();

    $app['debug'] = true;

    $app->register(new Silex\Provider\TwigServiceProvider(), ['twig.path' => __DIR__."/../views"]);

    $server = "mysql:host=localhost:8889;dbname=registrar";
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app->get('/', function() use ($app) {
        return $app['twig']->render('home.html.twig');
    });

    $app->get('/students', function() use ($app) {
        return $app['twig']->render('students.html.twig', array('students' => Student::getAll()));
    });

    $app->post('/student_add', function() use ($app) {
        $name = $_POST['name'];
        $enrollment_date = $_POST['enrollment-date'];
        $new_student = new Student($name, $enrollment_date);
        $new_student->save();
        return $app->redirect('/students');
    });

    $app->get('/courses', function() use ($app) {
        return $app['twig']->render('courses.html.twig', ['courses' => Course::getAll()]);
    });

    $app->post('/course_add', function() use ($app) {
        $name = $_POST['name'];
        $number = $_POST['number'];
        $new_course = new Course($name, $number);
        $new_course->save();
        return $app->redirect('/courses');
    });

    return $app;
?>
