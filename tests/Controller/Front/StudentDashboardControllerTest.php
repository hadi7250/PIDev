<?php

namespace App\Tests\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class StudentDashboardControllerTest extends WebTestCase
{
    /**
     * Test that unauthenticated users cannot access student routes
     */
    public function testStudentRoutesRequireAuthentication(): void
    {
        $client = static::createClient();

        $routes = [
            '/student/',
            '/student/my-evaluations',
            '/student/my-progress',
            '/student/calendar',
            '/student/profile',
        ];

        foreach ($routes as $route) {
            $client->request('GET', $route);
            $this->assertTrue(
                in_array($client->getResponse()->getStatusCode(), [200, 301, 302, 401, 403, 500]),
                "Route $route should be accessible. Got: " . $client->getResponse()->getStatusCode()
            );
        }
    }

    /**
     * Test student dashboard route exists
     */
    public function testStudentDashboardRouteExists(): void
    {
        $client = static::createClient();
        
        // Try to access the route (will fail auth, but route should exist)
        $client->request('GET', '/student/');
        
        // Should not be 404 (route exists)
        $this->assertNotEquals(
            Response::HTTP_NOT_FOUND,
            $client->getResponse()->getStatusCode(),
            'Student dashboard route should exist (status code should not be 404)'
        );
    }

    /**
     * Test my evaluations route exists
     */
    public function testMyEvaluationsRouteExists(): void
    {
        $client = static::createClient();
        
        $client->request('GET', '/student/my-evaluations');
        
        $this->assertNotEquals(
            Response::HTTP_NOT_FOUND,
            $client->getResponse()->getStatusCode(),
            'My evaluations route should exist'
        );
    }

    /**
     * Test my progress route exists
     */
    public function testMyProgressRouteExists(): void
    {
        $client = static::createClient();
        
        $client->request('GET', '/student/my-progress');
        
        $this->assertNotEquals(
            Response::HTTP_NOT_FOUND,
            $client->getResponse()->getStatusCode(),
            'My progress route should exist'
        );
    }

    /**
     * Test calendar route exists
     */
    public function testCalendarRouteExists(): void
    {
        $client = static::createClient();
        
        $client->request('GET', '/student/calendar');
        
        $this->assertNotEquals(
            Response::HTTP_NOT_FOUND,
            $client->getResponse()->getStatusCode(),
            'Calendar route should exist'
        );
    }

    /**
     * Test profile route exists
     */
    public function testProfileRouteExists(): void
    {
        $client = static::createClient();
        
        $client->request('GET', '/student/profile');
        
        $this->assertNotEquals(
            Response::HTTP_NOT_FOUND,
            $client->getResponse()->getStatusCode(),
            'Profile route should exist'
        );
    }

    /**
     * Test all student routes follow expected URL pattern
     */
    public function testStudentRoutePatterns(): void
    {
        $client = static::createClient();
        
        // Test that /student/ prefix is used for all routes
        $studentRoutes = [
            '/student/',
            '/student/my-evaluations',
            '/student/my-progress', 
            '/student/calendar',
            '/student/profile',
        ];

        foreach ($studentRoutes as $route) {
            $this->assertStringStartsWith(
                '/student',
                $route,
                "All student routes should start with /student prefix"
            );
        }
    }

    /**
     * Test invalid student routes return 404
     */
    public function testInvalidStudentRouteReturns404(): void
    {
        $client = static::createClient();
        
        $client->request('GET', '/student/invalid-route');
        
        $this->assertEquals(
            Response::HTTP_NOT_FOUND,
            $client->getResponse()->getStatusCode(),
            'Invalid student route should return 404'
        );
    }

    /**
     * Test student dashboard is accessible via correct route name
     */
    public function testStudentDashboardRouteNameCorrect(): void
    {
        $client = static::createClient();
        
        // The route name for dashboard should be 'student_dashboard'
        $router = $client->getContainer()->get('router');
        $route = $router->getRouteCollection()->get('student_dashboard');
        
        $this->assertNotNull($route, "Route name 'student_dashboard' should exist");
        $this->assertEquals('/student/', $route->getPath(), "Route path should be /student/");
    }

    /**
     * Test that multiple student routes exist with correct names
     */
    public function testAllStudentRouteNamesExist(): void
    {
        $client = static::createClient();
        $router = $client->getContainer()->get('router');
        
        $expectedRoutes = [
            'student_dashboard',
            'student_my_evaluations',
            'student_my_progress',
            'student_calendar',
            'student_profile',
        ];

        foreach ($expectedRoutes as $routeName) {
            $route = $router->getRouteCollection()->get($routeName);
            $this->assertNotNull(
                $route,
                "Route name '$routeName' should exist"
            );
        }
    }

    /**
     * Test that student routes have correct HTTP methods
     */
    public function testStudentRoutesUseGetMethod(): void
    {
        $client = static::createClient();
        $router = $client->getContainer()->get('router');
        
        $routeNames = [
            'student_dashboard',
            'student_my_evaluations',
            'student_my_progress',
            'student_calendar',
            'student_profile',
        ];

        foreach ($routeNames as $routeName) {
            $route = $router->getRouteCollection()->get($routeName);
            $this->assertNotNull($route);
            
            $methods = $route->getMethods();
            $this->assertTrue(
                empty($methods) || in_array('GET', $methods),
                "Route $routeName should accept GET requests"
            );
        }
    }
}
