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

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    $app->get('/', function() use ($app) {
        return $app['twig']->render('home.html.twig');
    });

    $app->get('/students', function() use ($app) {
        return $app['twig']->render('students.html.twig', array('students' => Student::getAll()));
    });

    $app->get('/students/{id}', function($id) use ($app) {
        $student = Student::find($id);
        return $app['twig']->render('student.html.twig', array('student' => $student, 'courses' => $student->getCourses(), 'all_courses' => Course::getAll()));
    });

    $app->post('/students/{id}/enroll', function($id) use ($app) {
        $student = Student::find($id);
        $student->addCourse($_POST['course']);
        return $app->redirect('/students/'.$id);
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

    $app->delete('/courses', function() use ($app) {
        Course::deleteAll();
        return $app->redirect('/courses');
    });

    $app->get('/courses/{id}', function($id) use ($app) {
        $course = Course::find($id);
        return $app['twig']->render('course.html.twig', ['course' => $course, 'students' => $course->getStudents()]);
    });

    $app->get('/courses/{id}/edit', function($id) use ($app) {
        $course = Course::find($id);
        return $app['twig']->render('edit_course.html.twig', ['course' => $course]);
    });

    $app->patch('/courses/{id}', function($id) use ($app) {
        $course = Course::find($id);
        $name = $_POST['name'];
        $course->updateName($name);
        return $app->redirect('/courses/'.$id);
    });

    $app->delete('/courses/{id}', function($id) use ($app) {
        $course = Course::find($id);
        $course->delete();
        return $app->redirect('/courses');
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
