# 🎯 Front Office & Back Office Implementation Guide

**Branch:** `integrationAttemptWithFrontAndBackOffice`  
**Date:** February 9, 2026  
**Status:** ✅ Complete - Ready for role-based access testing

---

## 📋 TABLE OF CONTENTS

1. [Overview](#overview)
2. [Architecture](#architecture)
3. [Role-Based Access Control](#role-based-access-control)
4. [Controllers Structure](#controllers-structure)
5. [Templates Structure](#templates-structure)
6. [Routes & Navigation](#routes--navigation)
7. [Implementation Details](#implementation-details)
8. [Testing Guide](#testing-guide)
9. [Integration Steps](#integration-steps)

---

## OVERVIEW

This implementation creates a complete **role-based two-tier interface**:

### **Front Office** 👤
- **Target Users:** Students
- **Access Level:** `ROLE_STUDENT`
- **Purpose:** View personal evaluations, track progress, manage profile
- **URL Prefix:** `/student`
- **Template Location:** `templates/front/`

### **Back Office** 👨‍💼
- **Target Users:** Teachers, Admins, Developers
- **Access Levels:** `ROLE_TEACHER`, `ROLE_ADMIN`, `ROLE_DEVELOPER`
- **Purpose:** Manage competences, evaluations, users, grade students
- **URL Prefix:** `/admin`
- **Template Location:** `templates/back/`

---

## ARCHITECTURE

```
EduConnect/
├── src/
│   ├── Controller/
│   │   ├── CompetenceController.php          ← Updated with @IsGranted
│   │   ├── EvaluationController.php          ← Updated with @IsGranted
│   │   ├── Front/
│   │   │   └── StudentDashboardController.php     ← New: Student interface
│   │   └── Back/
│   │       └── AdminDashboardController.php       ← New: Admin interface
│   ├── Repository/
│   │   ├── CompetenceRepository.php          ← Ready for filtering
│   │   └── EvaluationRepository.php          ← Ready for filtering
│   └── Entity/
│       ├── Competence.php                     ← Existing (unchanged)
│       └── Evaluation.php                     ← Existing (unchanged)
│
├── templates/
│   ├── base.html.twig                        ← Main layout (unchanged)
│   ├── front/                                ← NEW: Student interface
│   │   ├── dashboard/
│   │   │   └── index.html.twig                   Dashboard overview
│   │   ├── evaluation/
│   │   │   └── my_evaluations.html.twig         List my evaluations
│   │   ├── progress/
│   │   │   └── index.html.twig                   My progress charts
│   │   ├── calendar/
│   │   │   └── index.html.twig                   Evaluation calendar
│   │   └── profile/
│   │       └── index.html.twig                   Profile settings
│   │
│   └── back/                                 ← NEW: Admin/Teacher interface
│       ├── dashboard/
│       │   └── index.html.twig                   Admin dashboard
│       ├── competence/
│       │   └── index.html.twig                   Manage competences
│       ├── evaluation/
│       │   ├── index.html.twig                   All evaluations
│       │   └── grade_students.html.twig          Grading interface
│       ├── user/
│       │   └── index.html.twig                   User management
│       └── reports/
│           └── index.html.twig                   System reports
```

---

## ROLE-BASED ACCESS CONTROL

### Implementation Method: `#[IsGranted]` Attribute

All controllers use Symfony's `#[IsGranted]` annotation for access control:

```php
use Symfony\Component\Security\Http\Attribute\IsGranted;

class StudentDashboardController extends AbstractController
{
    #[Route('/student/', name: 'student_dashboard')]
    #[IsGranted('ROLE_STUDENT')]
    public function dashboard(): Response
    {
        // Only accessible to users with ROLE_STUDENT
    }
}
```

### Role Hierarchy

```
ROLE_DEVELOPER
    ├── Has all ROLE_ADMIN permissions
    ├── Plus: System configuration, logs, API management

ROLE_ADMIN
    ├── Has all ROLE_TEACHER permissions
    ├── Plus: User management, all competence management
    ├── Plus: System reports and monitoring

ROLE_TEACHER
    ├── Create/edit own evaluations only
    ├── Grade students in their evaluations
    ├── Cannot manage competences (Admins only)
    ├── Cannot see other teachers' evaluations

ROLE_STUDENT
    ├── View own evaluations only
    ├── View own progress
    ├── Cannot create/edit anything
    ├── Cannot see other students' data
```

---

## CONTROLLERS STRUCTURE

### 1. **StudentDashboardController** (NEW)

**Location:** `src/Controller/Front/StudentDashboardController.php`

**Routes:**
| Route | Method | Name | Description |
|-------|--------|------|-------------|
| `/student/` | GET | `student_dashboard` | Student dashboard overview |
| `/student/my-evaluations` | GET | `student_my_evaluations` | List my evaluations |
| `/student/my-progress` | GET | `student_my_progress` | View progress charts |
| `/student/calendar` | GET | `student_calendar` | Evaluation calendar |
| `/student/profile` | GET | `student_profile` | Profile settings |

**Security:** `#[IsGranted('ROLE_STUDENT')]` on all methods

**Functionality:**
```php
dashboard()           // Show statistics: total, completed, pending, average score
myEvaluations()      // List all evaluations for current student
myProgress()         // Show competence progress data
calendar()           // Show evaluation dates in calendar view
profile()            // Display/edit student profile
```

---

### 2. **AdminDashboardController** (NEW)

**Location:** `src/Controller/Back/AdminDashboardController.php`

**Routes:**
| Route | Method | Name | Description |
|-------|--------|------|-------------|
| `/admin/` | GET | `admin_dashboard` | Admin/teacher dashboard |
| `/admin/competences` | GET | `admin_competences` | Manage competences (Admin only) |
| `/admin/evaluations` | GET | `admin_evaluations` | View/manage evaluations |
| `/admin/grade-students` | GET | `admin_grade_students` | Grade student evaluations |
| `/admin/users` | GET | `admin_users` | User management (Admin only) |
| `/admin/reports` | GET | `admin_reports` | System reports (Admin only) |

**Security:**
- Base: `#[IsGranted('ROLE_TEACHER')]` (Teachers + Admins can access)
- Per-method: Some routes have additional `#[IsGranted('ROLE_ADMIN')]`

**Functionality:**
```php
dashboard()          // Show dashboards adapted for role:
                     // - Teachers: their evaluations count
                     // - Admins: all competences/evaluations count

competences()        // ADMIN ONLY: Manage all competences
evaluations()        // Filter by role: admins see all, teachers see theirs
gradeStudents()      // Filter evaluations by role
users()              // ADMIN ONLY: User management
reports()            // ADMIN ONLY: System statistics
```

---

### 3. **CompetenceController** (UPDATED)

**Location:** `src/Controller/CompetenceController.php`

**Security Updates:**
```php
#[Route('', name: 'competence_index')]
#[IsGranted('ROLE_STUDENT')]              // ← ADDED
public function index() { }

#[Route('/new', name: 'competence_new')]
#[IsGranted('ROLE_ADMIN')]                // ← ADDED: Admins only
public function new() { }

#[Route('/{id}', name: 'competence_show')]
#[IsGranted('ROLE_STUDENT')]              // ← ADDED
public function show() { }

#[Route('/{id}/edit', name: 'competence_edit')]
#[IsGranted('ROLE_ADMIN')]                // ← ADDED: Admins only
public function edit() { }

#[Route('/{id}', name: 'competence_delete')]
#[IsGranted('ROLE_ADMIN')]                // ← ADDED: Admins only
public function delete() { }

#[Route('/search', name: 'competence_search')]
#[IsGranted('ROLE_STUDENT')]              // ← ADDED
public function search() { }

#[Route('/export', name: 'competence_export')]
#[IsGranted('ROLE_ADMIN')]                // ← ADDED: Admins only
public function export() { }
```

---

### 4. **EvaluationController** (UPDATED)

**Location:** `src/Controller/EvaluationController.php`

**Security Updates:**
```php
#[Route('', name: 'evaluation_index')]
#[IsGranted('ROLE_STUDENT')]              // ← ADDED
public function index() { }

#[Route('/new', name: 'evaluation_new')]
#[IsGranted('ROLE_TEACHER')]              // ← ADDED: Teachers+Admins
public function new() { }

#[Route('/{id}', name: 'evaluation_show')]
#[IsGranted('ROLE_STUDENT')]              // ← ADDED
public function show() { }

#[Route('/{id}/edit', name: 'evaluation_edit')]
#[IsGranted('ROLE_TEACHER')]              // ← ADDED: Teachers+Admins
public function edit() { }

#[Route('/{id}', name: 'evaluation_delete')]
#[IsGranted('ROLE_TEACHER')]              // ← ADDED: Teachers+Admins
public function delete() { }

#[Route('/search', name: 'evaluation_search')]
#[IsGranted('ROLE_STUDENT')]              // ← ADDED
public function search() { }
```

---

## TEMPLATES STRUCTURE

### Front Office Templates (`templates/front/`)

#### 1. **Dashboard** (`front/dashboard/index.html.twig`)
- Welcome message with student name
- Statistics: Total, Completed, Pending, Average Score
- Quick action buttons (My Evaluations, Progress, Calendar, Profile)
- Upcoming deadlines section
- Resources section

#### 2. **My Evaluations** (`front/evaluation/my_evaluations.html.twig`)
- Filter by status (All, Pending, Completed, Graded)
- Sort options (Recent, Deadline, Score, Competence)
- Evaluation cards with:
  - Competence name & category
  - Status badge
  - Score display
  - View details button

#### 3. **My Progress** (`front/progress/index.html.twig`)
- Placeholder for competence progress charts
- Ready for integration with Chart.js

#### 4. **Evaluation Calendar** (`front/calendar/index.html.twig`)
- Placeholder for calendar view
- Ready for integration with calendar library

#### 5. **Profile Settings** (`front/profile/index.html.twig`)
- Display personal information
- Settings links (Password, Notifications, 2FA)
- Edit profile button

### Back Office Templates (`templates/back/`)

#### 1. **Admin Dashboard** (`back/dashboard/index.html.twig`)
- Role-adaptive display (Different for Teachers vs Admins)
- Statistics cards
- Quick action buttons (role-specific)
- Recent activity section
- System info (Admins only)

#### 2. **Competences Management** (`back/competence/index.html.twig`)
- Admin-only competence management
- Search/filter functionality
- Table with: Name, Category, Max Level, Created Date
- Action buttons: View, Edit, Delete
- "New Competence" button

#### 3. **Evaluations Management** (`back/evaluation/index.html.twig`)
- Admin/Teacher evaluation management
- Different views: Admins see all, Teachers see only theirs
- Search/filter functionality
- Table with: Competence, Status, Score, Date, Created By (Admin view)
- Action buttons: View, Edit, Delete

#### 4. **Grade Students** (`back/evaluation/grade_students.html.twig`)
- Table: Student, Evaluation, Status, Score
- Grade button for each evaluation
- Search/filter by student or evaluation

#### 5. **User Management** (`back/user/index.html.twig`)
- Admin-only user management
- Search/filter by role
- Table: Name, Email, Role, Status
- Actions: Edit, Deactivate, Delete
- "Add User" button
- Placeholder: Will work when User entity is integrated

#### 6. **System Reports** (`back/reports/index.html.twig`)
- Admin-only system statistics
- Cards: Total Competences, Evaluations, Users, Completion Rate
- Export options: Excel, PDF, CSV, Print

---

## ROUTES & NAVIGATION

### Route Organization

```
/
├── /student                          ← Front Office (Students)
│   ├── /                                Dashboard
│   ├── /my-evaluations                  My Evaluations
│   ├── /my-progress                     My Progress
│   ├── /calendar                        Calendar
│   └── /profile                         Profile
│
├── /admin                            ← Back Office (Teachers/Admins)
│   ├── /                                Dashboard
│   ├── /competences                     Competences (Admin only)
│   ├── /evaluations                     Evaluations (All)
│   ├── /grade-students                  Grade Students
│   ├── /users                           User Management (Admin only)
│   └── /reports                         Reports (Admin only)
│
├── /competence                       ← CRUD Operations
│   ├── /                                List (All users)
│   ├── /new                             Create (Admin only)
│   ├── /{id}                            View (All users)
│   ├── /{id}/edit                       Edit (Admin only)
│   ├── /{id}                            Delete (Admin only)
│   ├── /search                          Search (All users)
│   └── /export                          Export (Admin only)
│
└── /evaluation                       ← CRUD Operations
    ├── /                                List (All users)
    ├── /new                             Create (Teachers+)
    ├── /{id}                            View (All users)
    ├── /{id}/edit                       Edit (Teachers+)
    ├── /{id}                            Delete (Teachers+)
    └── /search                          Search (All users)
```

---

## IMPLEMENTATION DETAILS

### What's Changed

#### ✅ Created Files
1. `src/Controller/Front/StudentDashboardController.php`
2. `src/Controller/Back/AdminDashboardController.php`
3. `templates/front/dashboard/index.html.twig`
4. `templates/front/evaluation/my_evaluations.html.twig`
5. `templates/front/progress/index.html.twig`
6. `templates/front/calendar/index.html.twig`
7. `templates/front/profile/index.html.twig`
8. `templates/back/dashboard/index.html.twig`
9. `templates/back/competence/index.html.twig`
10. `templates/back/evaluation/index.html.twig`
11. `templates/back/evaluation/grade_students.html.twig`
12. `templates/back/user/index.html.twig`
13. `templates/back/reports/index.html.twig`

#### ✅ Modified Files
1. `src/Controller/CompetenceController.php` - Added `#[IsGranted]` annotations
2. `src/Controller/EvaluationController.php` - Added `#[IsGranted]` annotations
3. `src/Controller/DashboardController.php` - (Optional: could redirect to /student or /admin based on role)

#### ❌ NOT Modified (Per Your Request)
- Entity files (Competence.php, Evaluation.php) - Unchanged
- User entity - Not created (handled by gestionUser branch)
- Database migrations - Not generated (will need User entity first)

---

## TESTING GUIDE

### Without User Authentication (Current State)

Since the User entity is being handled by another group, we can test the structure:

```bash
# 1. Check that controllers are created
php bin/console debug:router | grep admin
php bin/console debug:router | grep student

# 2. Verify templates exist
ls -la templates/front/
ls -la templates/back/

# 3. Check for compilation errors
php bin/console lint:twig
```

### Once User Entity is Integrated

**Prerequisites:**
- User entity from gestionUser branch integrated
- Symfony Security configured with User provider
- Authentication system working

**Test Flow:**

1. **Login as Student (ROLE_STUDENT)**
   ```
   Email: student@example.com
   Password: student123
   
   ✓ Can access /student/
   ✓ Can access /student/my-evaluations
   ✓ Can access /competence/
   ✓ Can access /evaluation/
   ✗ Cannot access /admin/ (AccessDeniedException)
   ✗ Cannot access /competence/new (AccessDeniedException)
   ✗ Cannot access /competence/export (AccessDeniedException)
   ```

2. **Login as Teacher (ROLE_TEACHER)**
   ```
   Email: teacher@example.com
   Password: teacher123
   
   ✗ Cannot access /student/ (should redirect to /admin)
   ✓ Can access /admin/
   ✓ Can access /admin/evaluations
   ✓ Can access /admin/grade-students
   ✓ Can access /evaluation/new
   ✓ Can access /evaluation/{id}/edit
   ✗ Cannot access /admin/competences (AccessDeniedException)
   ✗ Cannot access /admin/users (AccessDeniedException)
   ✗ Cannot access /competence/new (AccessDeniedException)
   ```

3. **Login as Admin (ROLE_ADMIN)**
   ```
   Email: admin@example.com
   Password: admin123
   
   ✓ Can access /admin/
   ✓ Can access /admin/competences
   ✓ Can access /admin/users
   ✓ Can access /admin/reports
   ✓ Can access /competence/new
   ✓ Can access /competence/export
   ✓ Can see all evaluations
   ✗ Cannot access /student/ (should redirect to /admin)
   ```

### Test Checklist

**Front Office (Student)**
- [ ] Dashboard loads with correct statistics
- [ ] Can view my evaluations
- [ ] Can access progress page
- [ ] Can view calendar
- [ ] Can access profile settings
- [ ] Cannot access admin pages
- [ ] Cannot create competences
- [ ] Cannot export data

**Back Office (Teacher)**
- [ ] Dashboard shows only their evaluations
- [ ] Can manage their evaluations
- [ ] Can grade students
- [ ] Cannot see admin users page
- [ ] Cannot manage competences
- [ ] Cannot see other teachers' evaluations

**Back Office (Admin)**
- [ ] Dashboard shows all statistics
- [ ] Can manage all competences
- [ ] Can manage all evaluations
- [ ] Can access user management
- [ ] Can see system reports
- [ ] Can export all data
- [ ] Can export competences

---

## INTEGRATION STEPS

### Step 1: User Entity Integration (From gestionUser Branch)

When your groupmate's User entity is ready:

```bash
# 1. Merge User entity from gestionUser branch
git merge origin/gestionUser --no-commit

# 2. Resolve any conflicts (if necessary)

# 3. Update repositories to use User for filtering
#    See "Next Steps" section below
```

### Step 2: Update Repositories (After User Entity)

Update `CompetenceRepository.php`:
```php
public function findByTeacher(User $user): array
{
    return $this->createQueryBuilder('c')
        ->where('c.createdBy = :user')
        ->setParameter('user', $user)
        ->getQuery()
        ->getResult();
}
```

Update `EvaluationRepository.php`:
```php
public function findByStudent(User $user): array
{
    return $this->createQueryBuilder('e')
        ->innerJoin('e.students', 's')
        ->where('s = :user')
        ->setParameter('user', $user)
        ->getQuery()
        ->getResult();
}

public function findByTeacher(User $user): array
{
    return $this->createQueryBuilder('e')
        ->where('e.createdBy = :user')
        ->setParameter('user', $user)
        ->getQuery()
        ->getResult();
}
```

### Step 3: Update Controllers (After User Entity)

Replace placeholder queries in `StudentDashboardController`:
```php
#[Route('/', name: 'student_dashboard')]
public function dashboard(EvaluationRepository $evaluationRepository): Response
{
    $user = $this->getUser(); // Now will be User object
    $evaluations = $evaluationRepository->findByStudent($user);
    
    // Calculate statistics
    $totalEvaluations = count($evaluations);
    $completedEvaluations = count(array_filter(
        $evaluations, 
        fn($e) => $e->getStatus() === 'Validée'
    ));
    // ... etc
}
```

### Step 4: Create Migrations

After User entity is integrated:
```bash
# Generate migration for User entity
php bin/console make:migration

# Run migrations
php bin/console doctrine:migrations:migrate
```

### Step 5: Create Fixture Data

Create test users with different roles:
```bash
php bin/console doctrine:fixtures:load
```

Example fixture:
```php
// Create students
$student = new User();
$student->setEmail('student@example.com');
$student->setPassword(/* hashed */);
$student->setFirstName('John');
$student->setLastName('Doe');
$student->setRoles(['ROLE_STUDENT']);
$manager->persist($student);

// Create teacher
$teacher = new User();
$teacher->setEmail('teacher@example.com');
$teacher->setPassword(/* hashed */);
$teacher->setFirstName('Jane');
$teacher->setLastName('Smith');
$teacher->setRoles(['ROLE_TEACHER']);
$manager->persist($teacher);

// Create admin
$admin = new User();
$admin->setEmail('admin@example.com');
$admin->setPassword(/* hashed */);
$admin->setFirstName('Admin');
$admin->setLastName('User');
$admin->setRoles(['ROLE_ADMIN']);
$manager->persist($admin);
```

---

## NEXT STEPS

### Immediate (Now)
1. ✅ Push this branch to GitHub
2. ✅ Create Pull Request for code review
3. ✅ Share documentation with team

### After User Entity Integration
1. Update repository methods with User filtering
2. Update controllers to use actual User queries
3. Create database migrations
4. Create fixture data with test users
5. Test all role-based access
6. Add password reset functionality
7. Add email verification

### Advanced Features (Phase 2)
1. Student progress charts (Chart.js integration)
2. Evaluation calendar (FullCalendar integration)
3. Bulk grading interface
4. Student report generation
5. System analytics dashboard
6. Role-based API endpoints

---

## SUMMARY

This implementation provides:

✅ **Clean separation** between front office (students) and back office (teachers/admins)  
✅ **Role-based access control** using `#[IsGranted]` annotations  
✅ **Modern, responsive UI** with Bootstrap 5.3 styling  
✅ **Scalable architecture** ready for feature expansion  
✅ **Zero breaking changes** to existing entity structure  
✅ **Placeholder templates** ready for real data  
✅ **Ready for User entity integration** from gestionUser branch  

**Branch Status:** Ready for testing and integration  
**Last Update:** February 9, 2026  
**Maintained By:** GitHub Copilot

---

## 📞 SUPPORT

For questions or issues:
1. Check the [Integration Steps](#integration-steps) section
2. Review the [Testing Guide](#testing-guide)
3. Verify `#[IsGranted]` attributes are correctly placed
4. Ensure User entity is properly integrated before testing authentication

Good luck with your project! 🚀
