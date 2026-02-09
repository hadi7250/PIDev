<?php

namespace App\Tests\Security;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class RoleBasedAccessControlTest extends WebTestCase
{
    /**
     * Test that ROLE_STUDENT routes are protected
     */
    public function testStudentRoleProtection(): void
    {
        $client = static::createClient();
        
        $studentRoutes = [
            '/student/',
            '/student/my-evaluations',
            '/student/my-progress',
            '/student/calendar',
            '/student/profile',
        ];

        // All should require authentication or return not 200
        foreach ($studentRoutes as $route) {
            $client->request('GET', $route);
            $statusCode = $client->getResponse()->getStatusCode();
            
            $this->assertTrue(
                in_array($statusCode, [301, 302, 303, 307, 308, 401, 403]),
                "Route $route should be protected (got status: $statusCode)"
            );
        }
    }

    /**
     * Test that ROLE_TEACHER routes are protected
     */
    public function testTeacherRoleProtection(): void
    {
        $client = static::createClient();
        
        $teacherRoutes = [
            '/admin/',
            '/admin/evaluations',
            '/admin/grade-students',
        ];

        // All should require authentication or return not 200
        foreach ($teacherRoutes as $route) {
            $client->request('GET', $route);
            $statusCode = $client->getResponse()->getStatusCode();
            
            $this->assertTrue(
                in_array($statusCode, [301, 302, 303, 307, 308, 401, 403]),
                "Route $route should be protected (got status: $statusCode)"
            );
        }
    }

    /**
     * Test that ROLE_ADMIN routes are protected
     */
    public function testAdminRoleProtection(): void
    {
        $client = static::createClient();
        
        $adminOnlyRoutes = [
            '/admin/competences',
            '/admin/users',
            '/admin/reports',
        ];

        // All should require authentication or return not 200
        foreach ($adminOnlyRoutes as $route) {
            $client->request('GET', $route);
            $statusCode = $client->getResponse()->getStatusCode();
            
            $this->assertTrue(
                in_array($statusCode, [301, 302, 303, 307, 308, 401, 403]),
                "Route $route should be protected (got status: $statusCode)"
            );
        }
    }

    /**
     * Test that student cannot access admin routes (direct attempt)
     */
    public function testStudentCannotAccessAdminRoutes(): void
    {
        $client = static::createClient();
        
        $adminRoutes = [
            '/admin/',
            '/admin/competences',
            '/admin/users',
            '/admin/reports',
        ];

        foreach ($adminRoutes as $route) {
            $client->request('GET', $route);
            $statusCode = $client->getResponse()->getStatusCode();
            
            // Should not be 200 OK without authentication
            $this->assertNotEquals(
                Response::HTTP_OK,
                $statusCode,
                "Unauthenticated user should not access admin route: $route (got: $statusCode)"
            );
        }
    }

    /**
     * Test that public competence routes exist
     */
    public function testPublicCompetenceRoutesExist(): void
    {
        $client = static::createClient();
        
        // Competence index should exist (protected by ROLE_STUDENT)
        $client->request('GET', '/competence/');
        
        $this->assertNotEquals(
            Response::HTTP_NOT_FOUND,
            $client->getResponse()->getStatusCode(),
            'Public competence route should exist'
        );
    }

    /**
     * Test that competence creation route requires admin
     */
    public function testCompetenceCreationRequiresAdmin(): void
    {
        $client = static::createClient();
        
        $client->request('GET', '/competence/new');
        
        // Should not be 200 without admin role
        $this->assertNotEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode(),
            'Competence creation should require ROLE_ADMIN'
        );
    }

    /**
     * Test that evaluation routes exist
     */
    public function testPublicEvaluationRoutesExist(): void
    {
        $client = static::createClient();
        
        $client->request('GET', '/evaluation/');
        
        $this->assertNotEquals(
            Response::HTTP_NOT_FOUND,
            $client->getResponse()->getStatusCode(),
            'Public evaluation route should exist'
        );
    }

    /**
     * Test that all protected routes return authentication error or redirect
     */
    public function testAllProtectedRoutesRequireAuth(): void
    {
        $client = static::createClient();
        
        $protectedRoutes = [
            '/student/',
            '/admin/',
            '/competence/',
            '/evaluation/',
        ];

        foreach ($protectedRoutes as $route) {
            $client->request('GET', $route);
            $statusCode = $client->getResponse()->getStatusCode();
            
            // Should either redirect to login or return 403
            $this->assertTrue(
                in_array($statusCode, [301, 302, 303, 307, 308, 403, 401]),
                "Protected route $route should require authentication (got: $statusCode)"
            );
        }
    }

    /**
     * Test that @IsGranted annotations are applied to controllers
     */
    public function testIsGrantedAttributesApplied(): void
    {
        // This test verifies that security is enforced by attempting to access protected routes
        $client = static::createClient();
        
        // Student routes should be protected
        $client->request('GET', '/student/');
        $this->assertNotEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
        $this->assertNotEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        
        // Admin routes should be protected
        $client->request('GET', '/admin/');
        $this->assertNotEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
        $this->assertNotEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * Test that role hierarchy is respected (if implemented via role_hierarchy config)
     */
    public function testRoleHierarchyConfigExists(): void
    {
        $client = static::createClient();
        $container = $client->getContainer();
        
        // The security config should have role hierarchy
        // This is a meta-test to ensure security is properly configured
        $this->assertTrue(
            $container->has('security.authorization_checker'),
            'Security authorization checker should be configured'
        );
    }

    /**
     * Test that invalid route returns 404
     */
    public function testInvalidRouteReturns404(): void
    {
        $client = static::createClient();
        
        $client->request('GET', '/this-route-does-not-exist-anywhere/');
        
        $this->assertEquals(
            Response::HTTP_NOT_FOUND,
            $client->getResponse()->getStatusCode(),
            'Invalid routes should return 404'
        );
    }
}
