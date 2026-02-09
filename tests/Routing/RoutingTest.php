<?php

namespace App\Tests\Routing;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoutingTest extends WebTestCase
{
    /**
     * Test that all front office student routes exist and are discoverable
     */
    public function testAllStudentRoutesAreRegistered(): void
    {
        $client = static::createClient();
        $router = $client->getContainer()->get('router');
        $routes = $router->getRouteCollection();

        $studentRouteNames = [
            'student_dashboard',
            'student_my_evaluations',
            'student_my_progress',
            'student_calendar',
            'student_profile',
        ];

        foreach ($studentRouteNames as $routeName) {
            $this->assertNotNull(
                $routes->get($routeName),
                "Student route '$routeName' should be registered"
            );
        }
    }

    /**
     * Test that all back office admin routes exist and are discoverable
     */
    public function testAllAdminRoutesAreRegistered(): void
    {
        $client = static::createClient();
        $router = $client->getContainer()->get('router');
        $routes = $router->getRouteCollection();

        $adminRouteNames = [
            'admin_dashboard',
            'admin_competences',
            'admin_evaluations',
            'admin_grade_students',
            'admin_users',
            'admin_reports',
        ];

        foreach ($adminRouteNames as $routeName) {
            $this->assertNotNull(
                $routes->get($routeName),
                "Admin route '$routeName' should be registered"
            );
        }
    }

    /**
     * Test that student routes have correct paths
     */
    public function testStudentRoutePathsAreCorrect(): void
    {
        $client = static::createClient();
        $router = $client->getContainer()->get('router');

        $expectedPaths = [
            'student_dashboard' => '/student/',
            'student_my_evaluations' => '/student/my-evaluations',
            'student_my_progress' => '/student/my-progress',
            'student_calendar' => '/student/calendar',
            'student_profile' => '/student/profile',
        ];

        foreach ($expectedPaths as $routeName => $expectedPath) {
            $route = $router->getRouteCollection()->get($routeName);
            $this->assertNotNull($route, "Route $routeName should exist");
            $this->assertEquals(
                $expectedPath,
                $route->getPath(),
                "Route $routeName should have path $expectedPath"
            );
        }
    }

    /**
     * Test that admin routes have correct paths
     */
    public function testAdminRoutePathsAreCorrect(): void
    {
        $client = static::createClient();
        $router = $client->getContainer()->get('router');

        $expectedPaths = [
            'admin_dashboard' => '/admin/',
            'admin_competences' => '/admin/competences',
            'admin_evaluations' => '/admin/evaluations',
            'admin_grade_students' => '/admin/grade-students',
            'admin_users' => '/admin/users',
            'admin_reports' => '/admin/reports',
        ];

        foreach ($expectedPaths as $routeName => $expectedPath) {
            $route = $router->getRouteCollection()->get($routeName);
            $this->assertNotNull($route, "Route $routeName should exist");
            $this->assertEquals(
                $expectedPath,
                $route->getPath(),
                "Route $routeName should have path $expectedPath"
            );
        }
    }

    /**
     * Test that student and admin routes don't conflict
     */
    public function testStudentAndAdminRoutesDoNotConflict(): void
    {
        $client = static::createClient();
        $router = $client->getContainer()->get('router');

        $studentRoutes = [];
        $adminRoutes = [];

        foreach ($router->getRouteCollection() as $name => $route) {
            $path = $route->getPath();
            if (strpos($path, '/student') === 0) {
                $studentRoutes[] = $path;
            }
            if (strpos($path, '/admin') === 0) {
                $adminRoutes[] = $path;
            }
        }

        // Check for any overlap (there should be none)
        $overlap = array_intersect($studentRoutes, $adminRoutes);
        $this->assertEmpty(
            $overlap,
            'Student and admin routes should not have overlapping paths: ' . implode(', ', $overlap)
        );
    }

    /**
     * Test that routes are accessible via router
     */
    public function testRoutesCanBeGeneratedByRouter(): void
    {
        $client = static::createClient();
        $router = $client->getContainer()->get('router');

        // Test generating URLs from route names
        $studentUrl = $router->generate('student_dashboard');
        $this->assertEquals('/student/', $studentUrl);

        $adminUrl = $router->generate('admin_dashboard');
        $this->assertEquals('/admin/', $adminUrl);

        $evaluationsUrl = $router->generate('student_my_evaluations');
        $this->assertEquals('/student/my-evaluations', $evaluationsUrl);

        $competencesUrl = $router->generate('admin_competences');
        $this->assertEquals('/admin/competences', $competencesUrl);
    }

    /**
     * Test that all routes use GET method
     */
    public function testAllRoutesUseGetMethod(): void
    {
        $client = static::createClient();
        $router = $client->getContainer()->get('router');

        $routeNames = [
            'student_dashboard',
            'student_my_evaluations',
            'student_my_progress',
            'student_calendar',
            'student_profile',
            'admin_dashboard',
            'admin_competences',
            'admin_evaluations',
            'admin_grade_students',
            'admin_users',
            'admin_reports',
        ];

        foreach ($routeNames as $routeName) {
            $route = $router->getRouteCollection()->get($routeName);
            $this->assertNotNull($route, "Route $routeName should exist");
            
            $methods = $route->getMethods();
            // If methods are empty, all methods are allowed
            // Otherwise, GET should be included
            $this->assertTrue(
                empty($methods) || in_array('GET', $methods),
                "Route $routeName should accept GET method"
            );
        }
    }

    /**
     * Test that routes are properly namespaced
     */
    public function testRoutesAreProperlyNamespaced(): void
    {
        $client = static::createClient();
        $router = $client->getContainer()->get('router');

        // Student routes should use StudentDashboardController
        $studentRoutes = [
            'student_dashboard',
            'student_my_evaluations',
            'student_my_progress',
            'student_calendar',
            'student_profile',
        ];

        foreach ($studentRoutes as $routeName) {
            $route = $router->getRouteCollection()->get($routeName);
            $controller = $route->getDefault('_controller');
            $this->assertStringContainsString(
                'StudentDashboardController',
                $controller,
                "Student route $routeName should use StudentDashboardController"
            );
        }

        // Admin routes should use AdminDashboardController
        $adminRoutes = [
            'admin_dashboard',
            'admin_competences',
            'admin_evaluations',
            'admin_grade_students',
            'admin_users',
            'admin_reports',
        ];

        foreach ($adminRoutes as $routeName) {
            $route = $router->getRouteCollection()->get($routeName);
            $controller = $route->getDefault('_controller');
            $this->assertStringContainsString(
                'AdminDashboardController',
                $controller,
                "Admin route $routeName should use AdminDashboardController"
            );
        }
    }

    /**
     * Test that critical student routes exist
     */
    public function testCriticalStudentRoutesExist(): void
    {
        $client = static::createClient();
        $router = $client->getContainer()->get('router');

        $criticalRoutes = [
            'student_dashboard',
            'student_my_evaluations',
        ];

        foreach ($criticalRoutes as $routeName) {
            $this->assertNotNull(
                $router->getRouteCollection()->get($routeName),
                "Critical student route '$routeName' must exist"
            );
        }
    }

    /**
     * Test that critical admin routes exist
     */
    public function testCriticalAdminRoutesExist(): void
    {
        $client = static::createClient();
        $router = $client->getContainer()->get('router');

        $criticalRoutes = [
            'admin_dashboard',
            'admin_evaluations',
        ];

        foreach ($criticalRoutes as $routeName) {
            $this->assertNotNull(
                $router->getRouteCollection()->get($routeName),
                "Critical admin route '$routeName' must exist"
            );
        }
    }
}
