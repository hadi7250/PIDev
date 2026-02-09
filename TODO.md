# Todo List - Remove Security Restrictions

## Objective
Remove all user restrictions temporarily to allow developer access to both front and back office while Users module is being developed.

## Tasks

### Step 1: Start Symfony Development Server
- [ ] Start php bin/console server:run on port 8000

### Step 2: Remove Security Restrictions from Controllers
- [x] AdminDashboardController - Removed #[IsGranted] attributes
- [x] StudentDashboardController - Removed #[IsGranted] attributes
- [x] CompetenceController - Removed #[IsGranted] attributes
- [x] EvaluationController - Removed #[IsGranted] attributes

### Step 3: Test CRUD Operations
- [ ] Test Competence CRUD (/competence)
- [ ] Test Evaluation CRUD (/evaluation)
- [ ] Test Front Office (/student/*)
- [ ] Test Back Office (/admin/*)

### Step 4: Verify All Pages Load
- [ ] Verify dashboard loads
- [ ] Verify all templates render correctly
- [ ] Verify no 403 Forbidden errors

### Step 5: Compare Branches
- [x] Compare with gestionEvaluation branch

