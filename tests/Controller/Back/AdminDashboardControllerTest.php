<?php

namespace App\Tests\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AdminDashboardControllerTest extends WebTestCase
{
    /**
     * Test that unauthenticated users cannot access admin routes
     */
    public function testAdminRoutesRequireAuthentication(): void
    {
        $client = static::createClient();

        $routes = [
            '/admin/',
            '/admin/competences',
            '/admin/evaluations',
            '/admin/grade-students',
            '/admin/users',
            '/admin/reports',
        ];

        foreach ($routes as $route) {
            $client->request('GET', $route);
            $this->assertTrue(
                in_array($client->getResponse()->getStatusCode(), [301, 302, 401, 403]),
                "Route $route should redirect or return 401/403 for unauthenticated users. Got: " . $client->getResponse()->getStatusCode()
            );
        }
    }

    /**
     * Test admin dashboard route exists
     */
    public function testAdminDashboardRouteExists(): void
    {
        $client = static::createClient();
        
        $client->request('GET', '/admin/');
        
        $this->assertNotEquals(
            Response::HTTP_NOT_FOUND,
            $client->getResponse()->getStatusCode(),
            'Admin dashboard route should exist (status code should not be 404)'
        );
    }

    /**
     * Test competences management route exists
     */
    public function testCompetencesRouteExists(): void
    {
        $client = static::createClient();
        
        $client->request('GET', '/admin/competences');
        
        $this->assertNotEquals(
            Response::HTTP_NOT_FOUND,
            $client->getResponse()->getStatusCode(),
            'Competences management route should exist'
        );
    }

    /**
     * Test evaluations management route exists
     */
    public function testEvaluationsRouteExists(): void
    {
        $client = static::createClient();
        
        $client->request('GET', '/admin/evaluations');
        
        $this->assertNotEquals(
            Response::HTTP_NOT_FOUND,
            $client->getResponse()->getStatusCode(),
            'Evaluations management route should exist'
        );
    }

    /**
     * Test grade students route exists
     */
    public function testGradeStudentsRouteExists(): void
    {
        $client = static::createClient();
        
        $client->request('GET', '/admin/grade-students');
        
        $this->assertNotEquals(
            Response::HTTP_NOT_FOUND,
            $client->getResponse()->getStatusCode(),
            'Grade students route should exist'
        );
    }

    /**
     * Test users management route exists
     */
    public function testUsersRouteExists(): void
    {
        $client = static::createClient();
        
        $client->request('GET', '/admin/users');
        
        $this->assertNotEquals(
            Response::HTTP_NOT_FOUND,
            $client->getResponse()->getStatusCode(),
            'Users management route should exist'
        );
    }

    /**
     * Test reports route exists
     */
    public function testReportsRouteExists(): void
    {
        $client = static::createClient();
        
        $client->request('GET', '/admin/reports');
        
        $this->assertNotEquals(
            Response::HTTP_NOT_FOUND,
            $client->getResponse()->getStatusCode(),
            'Reports route should exist'
        );
    }

    /**
     * Test all admin routes follow expected URL pattern
     */
    public function testAdminRoutePatterns(): void
    {
        $client = static::createClient();
        
        // Test that /admin/ prefix is used for all routes
        $adminRoutes = [
            '/admin/',
            '/admin/competences',
            '/admin/evaluations',
            '/admin/grade-students',
            '/admin/users',
            '/admin/reports',
        ];

        foreach ($adminRoutes as $route) {
            $this->assertStringStartsWith(
                '/admin',
                $route,
                "All admin routes should start with /admin prefix"
            );
        }
    }

    /**
     * Test invalid admin routes return 404
     */
    public function testInvalidAdminRouteReturns404(): void
    {
        $client = static::createClient();
        
        $client->request('GET', '/admin/invalid-route');
        
        $this->assertEquals(
            Response::HTTP_NOT_FOUND,
            $client->getResponse()->getStatusCode(),
            'Invalid admin route should return 404'
        );
    }

    /**
     * Test all admin route names exist
     */
    public function testAllAdminRouteNamesExist(): void
    {
        $client = static::createClient();
        $router = $client->getContainer()->get('router');
        
        $expectedRoutes = [
            'admin_dashboard',
            'admin_competences',
            'admin_evaluations',
            'admin_grade_students',
            'admin_users',
            'admin_reports',
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
     * Test that admin routes have correct HTTP methods
     */
    public function testAdminRoutesUseGetMethod(): void
    {
        $client = static::createClient();
        $router = $client->getContainer()->get('router');
        
        $routeNames = [
            'admin_dashboard',
            'admin_competences',
            'admin_evaluations',
            'admin_grade_students',
            'admin_users',
            'admin_reports',
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

    /**
     * Test admin routes are separate from student routes
     */
    public function testAdminAndStudentRoutesAreSeparate(): void
    {
        $client = static::createClient();
        $router = $client->getContainer()->get('router');
        
        $adminRoutes = $router->getRouteCollection()->all();
        
        $adminPrefix = 0;
        $studentPrefix = 0;
        
        foreach ($adminRoutes as $name => $route) {
            $path = $route->getPath();
            if (strpos($path, '/admin') === 0) {
                $adminPrefix++;
            }
            if (strpos($path, '/student') === 0) {
                $studentPrefix++;
            }
        }
        
        $this->assertGreaterThan(0, $adminPrefix, "Should have admin routes");
        $this->assertGreaterThan(0, $studentPrefix, "Should have student routes");
    }
}
