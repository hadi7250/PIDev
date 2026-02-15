<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* dashboard/users_add.html.twig */
class __TwigTemplate_4e5e8176a5cb6833269e4b489352dea3 extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'title' => [$this, 'block_title'],
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 1
        return "dashboard/index.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "dashboard/users_add.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "dashboard/users_add.html.twig"));

        $this->parent = $this->load("dashboard/index.html.twig", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 3
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "title"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "title"));

        yield "User Management - Dashboard";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 5
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "content"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "content"));

        // line 6
        yield "<!--start page wrapper -->
<div class=\"page-wrapper\">
    <div class=\"page-content\">
        <!--breadcrumb-->
        <div class=\"breadcrumb-area\">
            <h1>User Management</h1>
            <nav aria-label=\"breadcrumb\">
                <ol class=\"breadcrumb mb-0\">
                    <li class=\"breadcrumb-item\"><a href=\"";
        // line 14
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("dashboard_index");
        yield "\">Home</a></li>
                    <li class=\"breadcrumb-item active\" aria-current=\"page\">User Management</li>
                </ol>
            </nav>
        </div>
        <!--end breadcrumb-->

        <div class=\"row\">
            <div class=\"col-12\">
                <div class=\"card\">
                    <div class=\"card-header d-flex justify-content-between align-items-center\">
                        <h5 class=\"mb-0\">User Management</h5>
                        <button class=\"btn btn-primary px-4\" data-bs-toggle=\"modal\" data-bs-target=\"#addUserModal\">
                            <i class=\"material-icons-outlined me-2\">add</i>Add New User
                        </button>
                    </div>
                    <div class=\"card-body\">
                        <!-- Search and Filter -->
                        <div class=\"row mb-4\">
                            <div class=\"col-md-6\">
                                <div class=\"header-search\">
                                    <div class=\"search-input\">
                                        <span class=\"material-icons-outlined\">search</span>
                                        <input type=\"text\" class=\"form-control\" id=\"searchUsers\" placeholder=\"Search users...\">
                                    </div>
                                </div>
                            </div>
                            <div class=\"col-md-6\">
                                <div class=\"d-flex gap-2\">
                                    <select class=\"form-select\" id=\"filterStatus\">
                                        <option value=\"\">All Status</option>
                                        <option value=\"active\">Active</option>
                                        <option value=\"inactive\">Inactive</option>
                                        <option value=\"pending\">Pending</option>
                                    </select>
                                    <select class=\"form-select\" id=\"filterRole\">
                                        <option value=\"\">All Roles</option>
                                        <option value=\"student\">Student</option>
                                        <option value=\"teacher\">Teacher</option>
                                        <option value=\"admin\">Admin</option>
                                    </select>
                                    <button class=\"btn btn-outline-primary\" onclick=\"exportUsers()\">
                                        <i class=\"material-icons-outlined me-2\">download</i>Export
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Users Table -->
                        <div class=\"table-responsive\">
                            <table class=\"table user-table\">
                                <thead>
                                    <tr>
                                        <th>
                                            <input class=\"form-check-input\" type=\"checkbox\" id=\"selectAll\">
                                        </th>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Last Active</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Table rows will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Bulk Actions -->
                        <div class=\"d-flex gap-2 mt-3\" id=\"bulkActions\" style=\"display: none;\">
                            <button class=\"btn btn-outline-danger\" onclick=\"deleteSelectedUsers()\">
                                <i class=\"material-icons-outlined me-2\">delete</i>Delete Selected
                            </button>
                            <button class=\"btn btn-outline-secondary\" onclick=\"deselectAll()\">
                                <i class=\"material-icons-outlined me-2\">close</i>Deselect All
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper-->

<!-- Add User Modal -->
<div class=\"modal fade\" id=\"addUserModal\" tabindex=\"-1\" aria-hidden=\"true\">
    <div class=\"modal-dialog modal-lg\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
                <h5 class=\"modal-title\">Add New User</h5>
                <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
            </div>
            <div class=\"modal-body\">
                <form id=\"addUserForm\">
                    <div class=\"row g-3\">
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">First Name</label>
                            <input type=\"text\" class=\"form-control\" name=\"firstName\" required>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Last Name</label>
                            <input type=\"text\" class=\"form-control\" name=\"lastName\" required>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Email</label>
                            <input type=\"email\" class=\"form-control\" name=\"email\" required>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Username</label>
                            <input type=\"text\" class=\"form-control\" name=\"username\" required>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Password</label>
                            <input type=\"password\" class=\"form-control\" name=\"password\" required>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Role</label>
                            <select class=\"form-select\" name=\"role\" required>
                                <option value=\"\">Select Role</option>
                                <option value=\"student\">Student</option>
                                <option value=\"teacher\">Teacher</option>
                                <option value=\"admin\">Admin</option>
                            </select>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Status</label>
                            <select class=\"form-select\" name=\"status\" required>
                                <option value=\"active\">Active</option>
                                <option value=\"inactive\">Inactive</option>
                                <option value=\"pending\">Pending</option>
                            </select>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Avatar</label>
                            <select class=\"form-select\" name=\"avatar\">
                                <option value=\"01.png\">Avatar 1</option>
                                <option value=\"02.png\">Avatar 2</option>
                                <option value=\"03.png\">Avatar 3</option>
                                <option value=\"04.png\">Avatar 4</option>
                                <option value=\"05.png\">Avatar 5</option>
                                <option value=\"06.png\">Avatar 6</option>
                            </select>
                        </div>
                        <div class=\"col-12\">
                            <label class=\"form-label\">Notes</label>
                            <textarea class=\"form-control\" name=\"notes\" rows=\"3\"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"btn btn-secondary\" data-bs-dismiss=\"modal\">Cancel</button>
                <button type=\"button\" class=\"btn btn-primary\" onclick=\"saveUser()\">Save User</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class=\"modal fade\" id=\"editUserModal\" tabindex=\"-1\" aria-hidden=\"true\">
    <div class=\"modal-dialog modal-lg\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
                <h5 class=\"modal-title\">Edit User</h5>
                <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
            </div>
            <div class=\"modal-body\">
                <form id=\"editUserForm\">
                    <input type=\"hidden\" id=\"editUserId\" name=\"id\">
                    <div class=\"row g-3\">
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">First Name</label>
                            <input type=\"text\" class=\"form-control\" id=\"editFirstName\" name=\"firstName\" required>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Last Name</label>
                            <input type=\"text\" class=\"form-control\" id=\"editLastName\" name=\"lastName\" required>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Email</label>
                            <input type=\"email\" class=\"form-control\" id=\"editEmail\" name=\"email\" required>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Username</label>
                            <input type=\"text\" class=\"form-control\" id=\"editUsername\" name=\"username\" required>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Role</label>
                            <select class=\"form-select\" id=\"editRole\" name=\"role\" required>
                                <option value=\"student\">Student</option>
                                <option value=\"teacher\">Teacher</option>
                                <option value=\"admin\">Admin</option>
                            </select>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Status</label>
                            <select class=\"form-select\" id=\"editStatus\" name=\"status\" required>
                                <option value=\"active\">Active</option>
                                <option value=\"inactive\">Inactive</option>
                                <option value=\"pending\">Pending</option>
                            </select>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Avatar</label>
                            <select class=\"form-select\" id=\"editAvatar\" name=\"avatar\">
                                <option value=\"01.png\">Avatar 1</option>
                                <option value=\"02.png\">Avatar 2</option>
                                <option value=\"03.png\">Avatar 3</option>
                                <option value=\"04.png\">Avatar 4</option>
                                <option value=\"05.png\">Avatar 5</option>
                                <option value=\"06.png\">Avatar 6</option>
                            </select>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Last Active</label>
                            <input type=\"text\" class=\"form-control\" id=\"editLastActive\" name=\"lastActive\" readonly>
                        </div>
                        <div class=\"col-12\">
                            <label class=\"form-label\">Notes</label>
                            <textarea class=\"form-control\" id=\"editNotes\" name=\"notes\" rows=\"3\"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"btn btn-secondary\" data-bs-dismiss=\"modal\">Cancel</button>
                <button type=\"button\" class=\"btn btn-primary\" onclick=\"updateUser()\">Update User</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class=\"modal fade\" id=\"deleteUserModal\" tabindex=\"-1\" aria-hidden=\"true\">
    <div class=\"modal-dialog\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
                <h5 class=\"modal-title\">Confirm Delete</h5>
                <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
            </div>
            <div class=\"modal-body\">
                <p>Are you sure you want to delete this user? This action cannot be undone.</p>
                <p class=\"fw-bold\" id=\"deleteUserName\"></p>
            </div>
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"btn btn-secondary\" data-bs-dismiss=\"modal\">Cancel</button>
                <button type=\"button\" class=\"btn btn-danger\" onclick=\"confirmDelete()\">Delete User</button>
            </div>
        </div>
    </div>
</div>

<!-- User Management JavaScript -->
<script>
// User Management CRUD Operations

// Sample initial data (replace with API calls)
let users = [
    {
        id: '1',
        firstName: 'John',
        lastName: 'Doe',
        email: 'john.doe@example.com',
        username: 'johndoe',
        avatar: '01.png',
        role: 'admin',
        status: 'active',
        lastActive: '2 hours ago',
        notes: 'System administrator'
    },
    {
        id: '2',
        firstName: 'Jane',
        lastName: 'Smith',
        email: 'jane.smith@example.com',
        username: 'janesmith',
        avatar: '02.png',
        role: 'teacher',
        status: 'active',
        lastActive: '5 minutes ago',
        notes: 'Mathematics teacher'
    },
    {
        id: '3',
        firstName: 'Mike',
        lastName: 'Johnson',
        email: 'mike.johnson@example.com',
        username: 'mikejohnson',
        avatar: '03.png',
        role: 'student',
        status: 'active',
        lastActive: '1 day ago',
        notes: 'Computer science student'
    },
    {
        id: '4',
        firstName: 'Sarah',
        lastName: 'Williams',
        email: 'sarah.williams@example.com',
        username: 'sarahwilliams',
        avatar: '04.png',
        role: 'student',
        status: 'inactive',
        lastActive: '3 days ago',
        notes: 'Biology student'
    },
    {
        id: '5',
        firstName: 'David',
        lastName: 'Brown',
        email: 'david.brown@example.com',
        username: 'davidbrown',
        avatar: '05.png',
        role: 'teacher',
        status: 'pending',
        lastActive: '1 week ago',
        notes: 'Physics teacher'
    }
];

let userToDelete = null;
let filteredUsers = [...users];

// Initialize table with users
function loadUsers() {
    const tbody = document.querySelector('.user-table tbody');
    tbody.innerHTML = '';
    
    filteredUsers.forEach(user => {
        const statusBadge = user.status === 'active' ? 'bg-success' : 
                           user.status === 'inactive' ? 'bg-danger' : 'bg-warning';
        
        const roleBadge = user.role === 'admin' ? 'bg-danger' : 
                         user.role === 'teacher' ? 'bg-warning' : 'bg-info';
        
        const row = `
            <tr id=\"user-\${user.id}\">
                <td>
                    <input class=\"form-check-input user-checkbox\" type=\"checkbox\" value=\"\${user.id}\">
                </td>
                <td>
                    <div class=\"d-flex align-items-center gap-3\">
                        <div class=\"user-pic\">
                            <img src=\"";
        // line 360
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("Back_Office/assets/images/avatars/"), "html", null, true);
        yield "\${user.avatar}\" class=\"rounded-circle\" width=\"40\" height=\"40\" alt=\"\">
                        </div>
                        <div>
                            <p class=\"mb-0 user-name fw-bold\">\${user.firstName} \${user.lastName}</p>
                            <small class=\"text-muted\">@\${user.username}</small>
                        </div>
                    </div>
                </td>
                <td>
                    <a href=\"mailto:\${user.email}\" class=\"text-decoration-none\">\${user.email}</a>
                </td>
                <td>
                    <span class=\"badge \${roleBadge}\">\${user.role}</span>
                </td>
                <td>
                    <span class=\"badge \${statusBadge}\">\${user.status}</span>
                </td>
                <td>\${user.lastActive}</td>
                <td>
                    <div class=\"d-flex gap-2\">
                        <button class=\"btn btn-sm btn-outline-primary\" onclick=\"editUser('\${user.id}')\" title=\"Edit\">
                            <i class=\"material-icons-outlined\">edit</i>
                        </button>
                        <button class=\"btn btn-sm btn-outline-danger\" onclick=\"deleteUser('\${user.id}', '\${user.firstName} \${user.lastName}')\" title=\"Delete\">
                            <i class=\"material-icons-outlined\">delete</i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
    
    updateBulkActionsVisibility();
}

// Save new user
function saveUser() {
    const form = document.getElementById('addUserForm');
    const formData = new FormData(form);
    
    const newUser = {
        id: Date.now().toString(),
        firstName: formData.get('firstName'),
        lastName: formData.get('lastName'),
        email: formData.get('email'),
        username: formData.get('username'),
        avatar: formData.get('avatar') || '01.png',
        role: formData.get('role'),
        status: formData.get('status'),
        lastActive: 'Just now',
        notes: formData.get('notes')
    };
    
    users.push(newUser);
    filteredUsers = [...users];
    loadUsers();
    
    // Close modal and reset form
    const modal = bootstrap.Modal.getInstance(document.getElementById('addUserModal'));
    modal.hide();
    form.reset();
    
    // Show success notification
    showNotification('User added successfully!', 'success');
}

// Edit user
function editUser(userId) {
    const user = users.find(u => u.id === userId);
    if (!user) return;
    
    // Populate edit form
    document.getElementById('editUserId').value = user.id;
    document.getElementById('editFirstName').value = user.firstName;
    document.getElementById('editLastName').value = user.lastName;
    document.getElementById('editEmail').value = user.email;
    document.getElementById('editUsername').value = user.username;
    document.getElementById('editRole').value = user.role;
    document.getElementById('editStatus').value = user.status;
    document.getElementById('editAvatar').value = user.avatar;
    document.getElementById('editLastActive').value = user.lastActive;
    document.getElementById('editNotes').value = user.notes || '';
    
    // Show edit modal
    const editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
    editModal.show();
}

// Update user
function updateUser() {
    const userId = document.getElementById('editUserId').value;
    const userIndex = users.findIndex(u => u.id === userId);
    
    if (userIndex !== -1) {
        users[userIndex] = {
            ...users[userIndex],
            firstName: document.getElementById('editFirstName').value,
            lastName: document.getElementById('editLastName').value,
            email: document.getElementById('editEmail').value,
            username: document.getElementById('editUsername').value,
            role: document.getElementById('editRole').value,
            status: document.getElementById('editStatus').value,
            avatar: document.getElementById('editAvatar').value,
            notes: document.getElementById('editNotes').value
        };
        
        filteredUsers = [...users];
        loadUsers();
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('editUserModal'));
        modal.hide();
        
        // Show success notification
        showNotification('User updated successfully!', 'success');
    }
}

// Delete user confirmation
function deleteUser(userId, userName) {
    userToDelete = userId;
    document.getElementById('deleteUserName').textContent = userName;
    
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
    deleteModal.show();
}

// Confirm delete
function confirmDelete() {
    if (userToDelete) {
        users = users.filter(user => user.id !== userToDelete);
        filteredUsers = [...users];
        loadUsers();
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteUserModal'));
        modal.hide();
        
        // Show success notification
        showNotification('User deleted successfully!', 'success');
        
        userToDelete = null;
    }
}

// Bulk actions
function deleteSelectedUsers() {
    const checkboxes = document.querySelectorAll('.user-checkbox:checked');
    const selectedIds = Array.from(checkboxes).map(cb => cb.value);
    
    if (selectedIds.length === 0) {
        showNotification('Please select users to delete', 'warning');
        return;
    }
    
    if (confirm(`Are you sure you want to delete \${selectedIds.length} user(s)?`)) {
        users = users.filter(user => !selectedIds.includes(user.id));
        filteredUsers = [...users];
        loadUsers();
        showNotification(`\${selectedIds.length} user(s) deleted successfully!`, 'success');
    }
}

function deselectAll() {
    document.querySelectorAll('.user-checkbox').forEach(cb => cb.checked = false);
    updateBulkActionsVisibility();
}

function updateBulkActionsVisibility() {
    const checkboxes = document.querySelectorAll('.user-checkbox:checked');
    const bulkActions = document.getElementById('bulkActions');
    bulkActions.style.display = checkboxes.length > 0 ? 'flex' : 'none';
}

// Export users
function exportUsers() {
    const csvContent = \"data:text/csv;charset=utf-8,\" 
        + \"ID,First Name,Last Name,Email,Username,Role,Status,Last Active\\n\"
        + filteredUsers.map(user => 
            `\${user.id},\${user.firstName},\${user.lastName},\${user.email},\${user.username},\${user.role},\${user.status},\${user.lastActive}` 
        ).join(\"\\n\");
    
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement(\"a\");
    link.setAttribute(\"href\", encodedUri);
    link.setAttribute(\"download\", \"users_export.csv\");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Search and filter functionality
function filterUsers() {
    const searchTerm = document.getElementById('searchUsers').value.toLowerCase();
    const statusFilter = document.getElementById('filterStatus').value;
    const roleFilter = document.getElementById('filterRole').value;
    
    filteredUsers = users.filter(user => {
        const matchesSearch = user.firstName.toLowerCase().includes(searchTerm) ||
                            user.lastName.toLowerCase().includes(searchTerm) ||
                            user.email.toLowerCase().includes(searchTerm) ||
                            user.username.toLowerCase().includes(searchTerm);
        const matchesStatus = !statusFilter || user.status === statusFilter;
        const matchesRole = !roleFilter || user.role === roleFilter;
        
        return matchesSearch && matchesStatus && matchesRole;
    });
    
    loadUsers();
}

// Notification function
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-\${type === 'success' ? 'success' : type === 'warning' ? 'warning' : 'info'} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
    notification.style.zIndex = '9999';
    notification.innerHTML = `
        \${message}
        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadUsers();
    
    // Search functionality
    document.getElementById('searchUsers').addEventListener('input', filterUsers);
    document.getElementById('filterStatus').addEventListener('change', filterUsers);
    document.getElementById('filterRole').addEventListener('change', filterUsers);
    
    // Select all functionality
    document.getElementById('selectAll').addEventListener('change', function() {
        document.querySelectorAll('.user-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
        updateBulkActionsVisibility();
    });
    
    // Update bulk actions when checkboxes change
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('user-checkbox')) {
            updateBulkActionsVisibility();
        }
    });
});
</script>

<style>
.user-pic img {
    object-fit: cover;
    border: 2px solid #e9ecef;
}

.user-table th {
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
}

.user-table td {
    vertical-align: middle;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.modal-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.modal-footer {
    background-color: #f8f9fa;
    border-top: 1px solid #dee2e6;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}
</style>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "dashboard/users_add.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  459 => 360,  110 => 14,  100 => 6,  87 => 5,  64 => 3,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'dashboard/index.html.twig' %}

{% block title %}User Management - Dashboard{% endblock %}

{% block content %}
<!--start page wrapper -->
<div class=\"page-wrapper\">
    <div class=\"page-content\">
        <!--breadcrumb-->
        <div class=\"breadcrumb-area\">
            <h1>User Management</h1>
            <nav aria-label=\"breadcrumb\">
                <ol class=\"breadcrumb mb-0\">
                    <li class=\"breadcrumb-item\"><a href=\"{{ path('dashboard_index') }}\">Home</a></li>
                    <li class=\"breadcrumb-item active\" aria-current=\"page\">User Management</li>
                </ol>
            </nav>
        </div>
        <!--end breadcrumb-->

        <div class=\"row\">
            <div class=\"col-12\">
                <div class=\"card\">
                    <div class=\"card-header d-flex justify-content-between align-items-center\">
                        <h5 class=\"mb-0\">User Management</h5>
                        <button class=\"btn btn-primary px-4\" data-bs-toggle=\"modal\" data-bs-target=\"#addUserModal\">
                            <i class=\"material-icons-outlined me-2\">add</i>Add New User
                        </button>
                    </div>
                    <div class=\"card-body\">
                        <!-- Search and Filter -->
                        <div class=\"row mb-4\">
                            <div class=\"col-md-6\">
                                <div class=\"header-search\">
                                    <div class=\"search-input\">
                                        <span class=\"material-icons-outlined\">search</span>
                                        <input type=\"text\" class=\"form-control\" id=\"searchUsers\" placeholder=\"Search users...\">
                                    </div>
                                </div>
                            </div>
                            <div class=\"col-md-6\">
                                <div class=\"d-flex gap-2\">
                                    <select class=\"form-select\" id=\"filterStatus\">
                                        <option value=\"\">All Status</option>
                                        <option value=\"active\">Active</option>
                                        <option value=\"inactive\">Inactive</option>
                                        <option value=\"pending\">Pending</option>
                                    </select>
                                    <select class=\"form-select\" id=\"filterRole\">
                                        <option value=\"\">All Roles</option>
                                        <option value=\"student\">Student</option>
                                        <option value=\"teacher\">Teacher</option>
                                        <option value=\"admin\">Admin</option>
                                    </select>
                                    <button class=\"btn btn-outline-primary\" onclick=\"exportUsers()\">
                                        <i class=\"material-icons-outlined me-2\">download</i>Export
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Users Table -->
                        <div class=\"table-responsive\">
                            <table class=\"table user-table\">
                                <thead>
                                    <tr>
                                        <th>
                                            <input class=\"form-check-input\" type=\"checkbox\" id=\"selectAll\">
                                        </th>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Last Active</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Table rows will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Bulk Actions -->
                        <div class=\"d-flex gap-2 mt-3\" id=\"bulkActions\" style=\"display: none;\">
                            <button class=\"btn btn-outline-danger\" onclick=\"deleteSelectedUsers()\">
                                <i class=\"material-icons-outlined me-2\">delete</i>Delete Selected
                            </button>
                            <button class=\"btn btn-outline-secondary\" onclick=\"deselectAll()\">
                                <i class=\"material-icons-outlined me-2\">close</i>Deselect All
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper-->

<!-- Add User Modal -->
<div class=\"modal fade\" id=\"addUserModal\" tabindex=\"-1\" aria-hidden=\"true\">
    <div class=\"modal-dialog modal-lg\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
                <h5 class=\"modal-title\">Add New User</h5>
                <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
            </div>
            <div class=\"modal-body\">
                <form id=\"addUserForm\">
                    <div class=\"row g-3\">
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">First Name</label>
                            <input type=\"text\" class=\"form-control\" name=\"firstName\" required>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Last Name</label>
                            <input type=\"text\" class=\"form-control\" name=\"lastName\" required>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Email</label>
                            <input type=\"email\" class=\"form-control\" name=\"email\" required>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Username</label>
                            <input type=\"text\" class=\"form-control\" name=\"username\" required>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Password</label>
                            <input type=\"password\" class=\"form-control\" name=\"password\" required>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Role</label>
                            <select class=\"form-select\" name=\"role\" required>
                                <option value=\"\">Select Role</option>
                                <option value=\"student\">Student</option>
                                <option value=\"teacher\">Teacher</option>
                                <option value=\"admin\">Admin</option>
                            </select>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Status</label>
                            <select class=\"form-select\" name=\"status\" required>
                                <option value=\"active\">Active</option>
                                <option value=\"inactive\">Inactive</option>
                                <option value=\"pending\">Pending</option>
                            </select>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Avatar</label>
                            <select class=\"form-select\" name=\"avatar\">
                                <option value=\"01.png\">Avatar 1</option>
                                <option value=\"02.png\">Avatar 2</option>
                                <option value=\"03.png\">Avatar 3</option>
                                <option value=\"04.png\">Avatar 4</option>
                                <option value=\"05.png\">Avatar 5</option>
                                <option value=\"06.png\">Avatar 6</option>
                            </select>
                        </div>
                        <div class=\"col-12\">
                            <label class=\"form-label\">Notes</label>
                            <textarea class=\"form-control\" name=\"notes\" rows=\"3\"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"btn btn-secondary\" data-bs-dismiss=\"modal\">Cancel</button>
                <button type=\"button\" class=\"btn btn-primary\" onclick=\"saveUser()\">Save User</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class=\"modal fade\" id=\"editUserModal\" tabindex=\"-1\" aria-hidden=\"true\">
    <div class=\"modal-dialog modal-lg\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
                <h5 class=\"modal-title\">Edit User</h5>
                <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
            </div>
            <div class=\"modal-body\">
                <form id=\"editUserForm\">
                    <input type=\"hidden\" id=\"editUserId\" name=\"id\">
                    <div class=\"row g-3\">
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">First Name</label>
                            <input type=\"text\" class=\"form-control\" id=\"editFirstName\" name=\"firstName\" required>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Last Name</label>
                            <input type=\"text\" class=\"form-control\" id=\"editLastName\" name=\"lastName\" required>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Email</label>
                            <input type=\"email\" class=\"form-control\" id=\"editEmail\" name=\"email\" required>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Username</label>
                            <input type=\"text\" class=\"form-control\" id=\"editUsername\" name=\"username\" required>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Role</label>
                            <select class=\"form-select\" id=\"editRole\" name=\"role\" required>
                                <option value=\"student\">Student</option>
                                <option value=\"teacher\">Teacher</option>
                                <option value=\"admin\">Admin</option>
                            </select>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Status</label>
                            <select class=\"form-select\" id=\"editStatus\" name=\"status\" required>
                                <option value=\"active\">Active</option>
                                <option value=\"inactive\">Inactive</option>
                                <option value=\"pending\">Pending</option>
                            </select>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Avatar</label>
                            <select class=\"form-select\" id=\"editAvatar\" name=\"avatar\">
                                <option value=\"01.png\">Avatar 1</option>
                                <option value=\"02.png\">Avatar 2</option>
                                <option value=\"03.png\">Avatar 3</option>
                                <option value=\"04.png\">Avatar 4</option>
                                <option value=\"05.png\">Avatar 5</option>
                                <option value=\"06.png\">Avatar 6</option>
                            </select>
                        </div>
                        <div class=\"col-md-6\">
                            <label class=\"form-label\">Last Active</label>
                            <input type=\"text\" class=\"form-control\" id=\"editLastActive\" name=\"lastActive\" readonly>
                        </div>
                        <div class=\"col-12\">
                            <label class=\"form-label\">Notes</label>
                            <textarea class=\"form-control\" id=\"editNotes\" name=\"notes\" rows=\"3\"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"btn btn-secondary\" data-bs-dismiss=\"modal\">Cancel</button>
                <button type=\"button\" class=\"btn btn-primary\" onclick=\"updateUser()\">Update User</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class=\"modal fade\" id=\"deleteUserModal\" tabindex=\"-1\" aria-hidden=\"true\">
    <div class=\"modal-dialog\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
                <h5 class=\"modal-title\">Confirm Delete</h5>
                <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
            </div>
            <div class=\"modal-body\">
                <p>Are you sure you want to delete this user? This action cannot be undone.</p>
                <p class=\"fw-bold\" id=\"deleteUserName\"></p>
            </div>
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"btn btn-secondary\" data-bs-dismiss=\"modal\">Cancel</button>
                <button type=\"button\" class=\"btn btn-danger\" onclick=\"confirmDelete()\">Delete User</button>
            </div>
        </div>
    </div>
</div>

<!-- User Management JavaScript -->
<script>
// User Management CRUD Operations

// Sample initial data (replace with API calls)
let users = [
    {
        id: '1',
        firstName: 'John',
        lastName: 'Doe',
        email: 'john.doe@example.com',
        username: 'johndoe',
        avatar: '01.png',
        role: 'admin',
        status: 'active',
        lastActive: '2 hours ago',
        notes: 'System administrator'
    },
    {
        id: '2',
        firstName: 'Jane',
        lastName: 'Smith',
        email: 'jane.smith@example.com',
        username: 'janesmith',
        avatar: '02.png',
        role: 'teacher',
        status: 'active',
        lastActive: '5 minutes ago',
        notes: 'Mathematics teacher'
    },
    {
        id: '3',
        firstName: 'Mike',
        lastName: 'Johnson',
        email: 'mike.johnson@example.com',
        username: 'mikejohnson',
        avatar: '03.png',
        role: 'student',
        status: 'active',
        lastActive: '1 day ago',
        notes: 'Computer science student'
    },
    {
        id: '4',
        firstName: 'Sarah',
        lastName: 'Williams',
        email: 'sarah.williams@example.com',
        username: 'sarahwilliams',
        avatar: '04.png',
        role: 'student',
        status: 'inactive',
        lastActive: '3 days ago',
        notes: 'Biology student'
    },
    {
        id: '5',
        firstName: 'David',
        lastName: 'Brown',
        email: 'david.brown@example.com',
        username: 'davidbrown',
        avatar: '05.png',
        role: 'teacher',
        status: 'pending',
        lastActive: '1 week ago',
        notes: 'Physics teacher'
    }
];

let userToDelete = null;
let filteredUsers = [...users];

// Initialize table with users
function loadUsers() {
    const tbody = document.querySelector('.user-table tbody');
    tbody.innerHTML = '';
    
    filteredUsers.forEach(user => {
        const statusBadge = user.status === 'active' ? 'bg-success' : 
                           user.status === 'inactive' ? 'bg-danger' : 'bg-warning';
        
        const roleBadge = user.role === 'admin' ? 'bg-danger' : 
                         user.role === 'teacher' ? 'bg-warning' : 'bg-info';
        
        const row = `
            <tr id=\"user-\${user.id}\">
                <td>
                    <input class=\"form-check-input user-checkbox\" type=\"checkbox\" value=\"\${user.id}\">
                </td>
                <td>
                    <div class=\"d-flex align-items-center gap-3\">
                        <div class=\"user-pic\">
                            <img src=\"{{ asset('Back_Office/assets/images/avatars/') }}\${user.avatar}\" class=\"rounded-circle\" width=\"40\" height=\"40\" alt=\"\">
                        </div>
                        <div>
                            <p class=\"mb-0 user-name fw-bold\">\${user.firstName} \${user.lastName}</p>
                            <small class=\"text-muted\">@\${user.username}</small>
                        </div>
                    </div>
                </td>
                <td>
                    <a href=\"mailto:\${user.email}\" class=\"text-decoration-none\">\${user.email}</a>
                </td>
                <td>
                    <span class=\"badge \${roleBadge}\">\${user.role}</span>
                </td>
                <td>
                    <span class=\"badge \${statusBadge}\">\${user.status}</span>
                </td>
                <td>\${user.lastActive}</td>
                <td>
                    <div class=\"d-flex gap-2\">
                        <button class=\"btn btn-sm btn-outline-primary\" onclick=\"editUser('\${user.id}')\" title=\"Edit\">
                            <i class=\"material-icons-outlined\">edit</i>
                        </button>
                        <button class=\"btn btn-sm btn-outline-danger\" onclick=\"deleteUser('\${user.id}', '\${user.firstName} \${user.lastName}')\" title=\"Delete\">
                            <i class=\"material-icons-outlined\">delete</i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
    
    updateBulkActionsVisibility();
}

// Save new user
function saveUser() {
    const form = document.getElementById('addUserForm');
    const formData = new FormData(form);
    
    const newUser = {
        id: Date.now().toString(),
        firstName: formData.get('firstName'),
        lastName: formData.get('lastName'),
        email: formData.get('email'),
        username: formData.get('username'),
        avatar: formData.get('avatar') || '01.png',
        role: formData.get('role'),
        status: formData.get('status'),
        lastActive: 'Just now',
        notes: formData.get('notes')
    };
    
    users.push(newUser);
    filteredUsers = [...users];
    loadUsers();
    
    // Close modal and reset form
    const modal = bootstrap.Modal.getInstance(document.getElementById('addUserModal'));
    modal.hide();
    form.reset();
    
    // Show success notification
    showNotification('User added successfully!', 'success');
}

// Edit user
function editUser(userId) {
    const user = users.find(u => u.id === userId);
    if (!user) return;
    
    // Populate edit form
    document.getElementById('editUserId').value = user.id;
    document.getElementById('editFirstName').value = user.firstName;
    document.getElementById('editLastName').value = user.lastName;
    document.getElementById('editEmail').value = user.email;
    document.getElementById('editUsername').value = user.username;
    document.getElementById('editRole').value = user.role;
    document.getElementById('editStatus').value = user.status;
    document.getElementById('editAvatar').value = user.avatar;
    document.getElementById('editLastActive').value = user.lastActive;
    document.getElementById('editNotes').value = user.notes || '';
    
    // Show edit modal
    const editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
    editModal.show();
}

// Update user
function updateUser() {
    const userId = document.getElementById('editUserId').value;
    const userIndex = users.findIndex(u => u.id === userId);
    
    if (userIndex !== -1) {
        users[userIndex] = {
            ...users[userIndex],
            firstName: document.getElementById('editFirstName').value,
            lastName: document.getElementById('editLastName').value,
            email: document.getElementById('editEmail').value,
            username: document.getElementById('editUsername').value,
            role: document.getElementById('editRole').value,
            status: document.getElementById('editStatus').value,
            avatar: document.getElementById('editAvatar').value,
            notes: document.getElementById('editNotes').value
        };
        
        filteredUsers = [...users];
        loadUsers();
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('editUserModal'));
        modal.hide();
        
        // Show success notification
        showNotification('User updated successfully!', 'success');
    }
}

// Delete user confirmation
function deleteUser(userId, userName) {
    userToDelete = userId;
    document.getElementById('deleteUserName').textContent = userName;
    
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
    deleteModal.show();
}

// Confirm delete
function confirmDelete() {
    if (userToDelete) {
        users = users.filter(user => user.id !== userToDelete);
        filteredUsers = [...users];
        loadUsers();
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteUserModal'));
        modal.hide();
        
        // Show success notification
        showNotification('User deleted successfully!', 'success');
        
        userToDelete = null;
    }
}

// Bulk actions
function deleteSelectedUsers() {
    const checkboxes = document.querySelectorAll('.user-checkbox:checked');
    const selectedIds = Array.from(checkboxes).map(cb => cb.value);
    
    if (selectedIds.length === 0) {
        showNotification('Please select users to delete', 'warning');
        return;
    }
    
    if (confirm(`Are you sure you want to delete \${selectedIds.length} user(s)?`)) {
        users = users.filter(user => !selectedIds.includes(user.id));
        filteredUsers = [...users];
        loadUsers();
        showNotification(`\${selectedIds.length} user(s) deleted successfully!`, 'success');
    }
}

function deselectAll() {
    document.querySelectorAll('.user-checkbox').forEach(cb => cb.checked = false);
    updateBulkActionsVisibility();
}

function updateBulkActionsVisibility() {
    const checkboxes = document.querySelectorAll('.user-checkbox:checked');
    const bulkActions = document.getElementById('bulkActions');
    bulkActions.style.display = checkboxes.length > 0 ? 'flex' : 'none';
}

// Export users
function exportUsers() {
    const csvContent = \"data:text/csv;charset=utf-8,\" 
        + \"ID,First Name,Last Name,Email,Username,Role,Status,Last Active\\n\"
        + filteredUsers.map(user => 
            `\${user.id},\${user.firstName},\${user.lastName},\${user.email},\${user.username},\${user.role},\${user.status},\${user.lastActive}` 
        ).join(\"\\n\");
    
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement(\"a\");
    link.setAttribute(\"href\", encodedUri);
    link.setAttribute(\"download\", \"users_export.csv\");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Search and filter functionality
function filterUsers() {
    const searchTerm = document.getElementById('searchUsers').value.toLowerCase();
    const statusFilter = document.getElementById('filterStatus').value;
    const roleFilter = document.getElementById('filterRole').value;
    
    filteredUsers = users.filter(user => {
        const matchesSearch = user.firstName.toLowerCase().includes(searchTerm) ||
                            user.lastName.toLowerCase().includes(searchTerm) ||
                            user.email.toLowerCase().includes(searchTerm) ||
                            user.username.toLowerCase().includes(searchTerm);
        const matchesStatus = !statusFilter || user.status === statusFilter;
        const matchesRole = !roleFilter || user.role === roleFilter;
        
        return matchesSearch && matchesStatus && matchesRole;
    });
    
    loadUsers();
}

// Notification function
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-\${type === 'success' ? 'success' : type === 'warning' ? 'warning' : 'info'} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
    notification.style.zIndex = '9999';
    notification.innerHTML = `
        \${message}
        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadUsers();
    
    // Search functionality
    document.getElementById('searchUsers').addEventListener('input', filterUsers);
    document.getElementById('filterStatus').addEventListener('change', filterUsers);
    document.getElementById('filterRole').addEventListener('change', filterUsers);
    
    // Select all functionality
    document.getElementById('selectAll').addEventListener('change', function() {
        document.querySelectorAll('.user-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
        updateBulkActionsVisibility();
    });
    
    // Update bulk actions when checkboxes change
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('user-checkbox')) {
            updateBulkActionsVisibility();
        }
    });
});
</script>

<style>
.user-pic img {
    object-fit: cover;
    border: 2px solid #e9ecef;
}

.user-table th {
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
}

.user-table td {
    vertical-align: middle;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.modal-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.modal-footer {
    background-color: #f8f9fa;
    border-top: 1px solid #dee2e6;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}
</style>
{% endblock %}
", "dashboard/users_add.html.twig", "C:\\Users\\rajhi\\pidev-education-app\\templates\\dashboard\\users_add.html.twig");
    }
}
