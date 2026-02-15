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

/* security/login_old.html.twig */
class __TwigTemplate_b89e9cffee1d1de4daa1488c2b93de02 extends Template
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

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "security/login_old.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "security/login_old.html.twig"));

        // line 1
        yield "<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>PIDEV Education - Login</title>
    
    <!-- Bootstrap CSS -->
    <link href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css\" rel=\"stylesheet\">
    <!-- Font Awesome -->
    <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css\">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .login-left {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .login-right {
            padding: 50px;
        }
        
        .brand-logo {
            width: 150px;
            margin-bottom: 30px;
        }
        
        .social-login-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            margin-bottom: 15px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s;
        }
        
        .social-login-btn:hover {
            background: #f8f9fa;
            border-color: #667eea;
        }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 30px 0;
            color: #666;
        }
        
        .divider::before, .divider::after {
            content: \"\";
            flex: 1;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .divider span {
            padding: 0 15px;
        }
        
        .form-control {
            padding: 15px;
            border-radius: 10px;
            border: 2px solid #e0e0e0;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            width: 100%;
        }
        
        .btn-login:hover {
            opacity: 0.9;
            color: white;
        }
        
        .forgot-link {
            color: #667eea;
            text-decoration: none;
        }
        
        .forgot-link:hover {
            text-decoration: underline;
        }
        
        .register-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        
        .register-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .login-left {
                padding: 30px;
            }
            .login-right {
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <div class=\"container\">
        <div class=\"row justify-content-center\">
            <div class=\"col-lg-10\">
                <div class=\"login-card\">
                    <div class=\"row g-0\">
                        <!-- Left Side - Branding & Info -->
                        <div class=\"col-lg-6 d-none d-lg-block\">
                            <div class=\"login-left\">
                                <div class=\"text-center mb-4\">
                                    <h1 class=\"display-5 fw-bold\">PIDEV Education</h1>
                                    <p class=\"lead\">Welcome Back!</p>
                                </div>
                                
                                <div class=\"features\">
                                    <div class=\"d-flex align-items-center mb-4\">
                                        <div class=\"feature-icon me-3\">
                                            <i class=\"fas fa-graduation-cap fa-2x\"></i>
                                        </div>
                                        <div>
                                            <h5>Learn Anywhere</h5>
                                            <p class=\"mb-0\">Access courses from anywhere, anytime</p>
                                        </div>
                                    </div>
                                    
                                    <div class=\"d-flex align-items-center mb-4\">
                                        <div class=\"feature-icon me-3\">
                                            <i class=\"fas fa-users fa-2x\"></i>
                                        </div>
                                        <div>
                                            <h5>Connect with Experts</h5>
                                            <p class=\"mb-0\">Network with entrepreneurs and investors</p>
                                        </div>
                                    </div>
                                    
                                    <div class=\"d-flex align-items-center\">
                                        <div class=\"feature-icon me-3\">
                                            <i class=\"fas fa-chart-line fa-2x\"></i>
                                        </div>
                                        <div>
                                            <h5>Track Progress</h5>
                                            <p class=\"mb-0\">Monitor your learning journey</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class=\"mt-5 text-center\">
                                    <p>Don't have an account?</p>
                                    <a href=\"";
        // line 192
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_register");
        yield "\" class=\"btn btn-outline-light btn-lg px-4\">
                                        Create Account
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Side - Login Form -->
                        <div class=\"col-lg-6\">
                            <div class=\"login-right\">
                                <div class=\"text-center mb-4 d-lg-none\">
                                    <h1 class=\"fw-bold\" style=\"color: #667eea;\">PIDEV Education</h1>
                                    <p class=\"text-muted\">Welcome Back!</p>
                                </div>
                                
                                <h2 class=\"fw-bold mb-1\">Get Started Now</h2>
                                <p class=\"text-muted mb-4\">Enter your credentials to login to your account</p>
                                
                                <!-- Social Login Buttons -->
                                <div class=\"mb-4\">
                                    <a href=\"#\" class=\"social-login-btn\">
                                        <i class=\"fab fa-google text-danger\"></i>
                                        <span>Sign in with Google</span>
                                    </a>
                                    <a href=\"#\" class=\"social-login-btn\">
                                        <i class=\"fab fa-facebook-f text-primary\"></i>
                                        <span>Sign in with Facebook</span>
                                    </a>
                                    <a href=\"#\" class=\"social-login-btn\">
                                        <i class=\"fab fa-linkedin-in text-info\"></i>
                                        <span>Sign in with LinkedIn</span>
                                    </a>
                                </div>
                                
                                <!-- Divider -->
                                <div class=\"divider\">
                                    <span>OR SIGN IN WITH</span>
                                </div>
                                
                                <!-- Login Form -->
                                <form method=\"post\" action=\"";
        // line 232
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_login");
        yield "\" novalidate=\"novalidate\">
                                    ";
        // line 233
        if ((($tmp = (isset($context["error"]) || array_key_exists("error", $context) ? $context["error"] : (function () { throw new RuntimeError('Variable "error" does not exist.', 233, $this->source); })())) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 234
            yield "                                        <div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">
                                            ";
            // line 235
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans(CoreExtension::getAttribute($this->env, $this->source, (isset($context["error"]) || array_key_exists("error", $context) ? $context["error"] : (function () { throw new RuntimeError('Variable "error" does not exist.', 235, $this->source); })()), "messageKey", [], "any", false, false, false, 235), CoreExtension::getAttribute($this->env, $this->source, (isset($context["error"]) || array_key_exists("error", $context) ? $context["error"] : (function () { throw new RuntimeError('Variable "error" does not exist.', 235, $this->source); })()), "messageData", [], "any", false, false, false, 235), "security"), "html", null, true);
            yield "
                                            <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\"></button>
                                        </div>
                                    ";
        }
        // line 239
        yield "                                    
                                    ";
        // line 240
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 240, $this->source); })()), "user", [], "any", false, false, false, 240)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 241
            yield "                                        <div class=\"alert alert-info\">
                                            You are logged in as ";
            // line 242
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 242, $this->source); })()), "user", [], "any", false, false, false, 242), "email", [], "any", false, false, false, 242), "html", null, true);
            yield ", <a href=\"";
            yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_logout");
            yield "\">Logout</a>
                                        </div>
                                    ";
        }
        // line 245
        yield "                                    
                                    <div class=\"mb-3\">
                                        <label for=\"username\" class=\"form-label\">Email</label>
                                        <input type=\"email\" value=\"";
        // line 248
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((isset($context["last_username"]) || array_key_exists("last_username", $context) ? $context["last_username"] : (function () { throw new RuntimeError('Variable "last_username" does not exist.', 248, $this->source); })()), "html", null, true);
        yield "\" name=\"_username\" id=\"username\" 
                                               class=\"form-control\" placeholder=\"jhon@example.com\" autofocus>
                                    </div>
                                    
                                    <div class=\"mb-3\">
                                        <label for=\"password\" class=\"form-label\">Password</label>
                                        <div class=\"input-group\">
                                            <input type=\"password\" name=\"_password\" id=\"password\" 
                                                   class=\"form-control\" placeholder=\"Enter Password\">
                                            <button class=\"btn btn-outline-secondary\" type=\"button\" id=\"togglePassword\">
                                                <i class=\"fas fa-eye\"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class=\"d-flex justify-content-between align-items-center mb-4\">
                                        <div class=\"form-check\">
                                            <input class=\"form-check-input\" type=\"checkbox\" id=\"remember_me\" name=\"_remember_me\">
                                            <label class=\"form-check-label\" for=\"remember_me\">
                                                Remember Me
                                            </label>
                                        </div>
                                        <a href=\"#\" class=\"forgot-link\">Forgot Password?</a>
                                    </div>
                                    
                                    <input type=\"hidden\" name=\"_csrf_token\" value=\"";
        // line 273
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderCsrfToken("authenticate"), "html", null, true);
        yield "\">
                                    
                                    <button type=\"submit\" class=\"btn btn-login mb-3\">Login</button>
                                    
                                    <div class=\"register-link d-lg-none\">
                                        <p>Don't have an account yet? <a href=\"";
        // line 278
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_register");
        yield "\">Sign up here</a></p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src=\"https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js\"></script>
    
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
        
        // Handle form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            const email = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            
            if (!email || !password) {
                e.preventDefault();
                alert('Please fill in all fields');
            }
        });
        
        // Disable HTML5 validation for form
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            if (form) {
                form.setAttribute('novalidate', 'novalidate');
                
                // Prevent default validation on submit
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                }, false);
                
                console.log('HTML5 validation disabled for login form');
            }
        });
    </script>
</body>
</html>";
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "security/login_old.html.twig";
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
        return array (  357 => 278,  349 => 273,  321 => 248,  316 => 245,  308 => 242,  305 => 241,  303 => 240,  300 => 239,  293 => 235,  290 => 234,  288 => 233,  284 => 232,  241 => 192,  48 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>PIDEV Education - Login</title>
    
    <!-- Bootstrap CSS -->
    <link href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css\" rel=\"stylesheet\">
    <!-- Font Awesome -->
    <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css\">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .login-left {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .login-right {
            padding: 50px;
        }
        
        .brand-logo {
            width: 150px;
            margin-bottom: 30px;
        }
        
        .social-login-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            margin-bottom: 15px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s;
        }
        
        .social-login-btn:hover {
            background: #f8f9fa;
            border-color: #667eea;
        }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 30px 0;
            color: #666;
        }
        
        .divider::before, .divider::after {
            content: \"\";
            flex: 1;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .divider span {
            padding: 0 15px;
        }
        
        .form-control {
            padding: 15px;
            border-radius: 10px;
            border: 2px solid #e0e0e0;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            width: 100%;
        }
        
        .btn-login:hover {
            opacity: 0.9;
            color: white;
        }
        
        .forgot-link {
            color: #667eea;
            text-decoration: none;
        }
        
        .forgot-link:hover {
            text-decoration: underline;
        }
        
        .register-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        
        .register-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .login-left {
                padding: 30px;
            }
            .login-right {
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <div class=\"container\">
        <div class=\"row justify-content-center\">
            <div class=\"col-lg-10\">
                <div class=\"login-card\">
                    <div class=\"row g-0\">
                        <!-- Left Side - Branding & Info -->
                        <div class=\"col-lg-6 d-none d-lg-block\">
                            <div class=\"login-left\">
                                <div class=\"text-center mb-4\">
                                    <h1 class=\"display-5 fw-bold\">PIDEV Education</h1>
                                    <p class=\"lead\">Welcome Back!</p>
                                </div>
                                
                                <div class=\"features\">
                                    <div class=\"d-flex align-items-center mb-4\">
                                        <div class=\"feature-icon me-3\">
                                            <i class=\"fas fa-graduation-cap fa-2x\"></i>
                                        </div>
                                        <div>
                                            <h5>Learn Anywhere</h5>
                                            <p class=\"mb-0\">Access courses from anywhere, anytime</p>
                                        </div>
                                    </div>
                                    
                                    <div class=\"d-flex align-items-center mb-4\">
                                        <div class=\"feature-icon me-3\">
                                            <i class=\"fas fa-users fa-2x\"></i>
                                        </div>
                                        <div>
                                            <h5>Connect with Experts</h5>
                                            <p class=\"mb-0\">Network with entrepreneurs and investors</p>
                                        </div>
                                    </div>
                                    
                                    <div class=\"d-flex align-items-center\">
                                        <div class=\"feature-icon me-3\">
                                            <i class=\"fas fa-chart-line fa-2x\"></i>
                                        </div>
                                        <div>
                                            <h5>Track Progress</h5>
                                            <p class=\"mb-0\">Monitor your learning journey</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class=\"mt-5 text-center\">
                                    <p>Don't have an account?</p>
                                    <a href=\"{{ path('app_register') }}\" class=\"btn btn-outline-light btn-lg px-4\">
                                        Create Account
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Side - Login Form -->
                        <div class=\"col-lg-6\">
                            <div class=\"login-right\">
                                <div class=\"text-center mb-4 d-lg-none\">
                                    <h1 class=\"fw-bold\" style=\"color: #667eea;\">PIDEV Education</h1>
                                    <p class=\"text-muted\">Welcome Back!</p>
                                </div>
                                
                                <h2 class=\"fw-bold mb-1\">Get Started Now</h2>
                                <p class=\"text-muted mb-4\">Enter your credentials to login to your account</p>
                                
                                <!-- Social Login Buttons -->
                                <div class=\"mb-4\">
                                    <a href=\"#\" class=\"social-login-btn\">
                                        <i class=\"fab fa-google text-danger\"></i>
                                        <span>Sign in with Google</span>
                                    </a>
                                    <a href=\"#\" class=\"social-login-btn\">
                                        <i class=\"fab fa-facebook-f text-primary\"></i>
                                        <span>Sign in with Facebook</span>
                                    </a>
                                    <a href=\"#\" class=\"social-login-btn\">
                                        <i class=\"fab fa-linkedin-in text-info\"></i>
                                        <span>Sign in with LinkedIn</span>
                                    </a>
                                </div>
                                
                                <!-- Divider -->
                                <div class=\"divider\">
                                    <span>OR SIGN IN WITH</span>
                                </div>
                                
                                <!-- Login Form -->
                                <form method=\"post\" action=\"{{ path('app_login') }}\" novalidate=\"novalidate\">
                                    {% if error %}
                                        <div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">
                                            {{ error.messageKey|trans(error.messageData, 'security') }}
                                            <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\"></button>
                                        </div>
                                    {% endif %}
                                    
                                    {% if app.user %}
                                        <div class=\"alert alert-info\">
                                            You are logged in as {{ app.user.email }}, <a href=\"{{ path('app_logout') }}\">Logout</a>
                                        </div>
                                    {% endif %}
                                    
                                    <div class=\"mb-3\">
                                        <label for=\"username\" class=\"form-label\">Email</label>
                                        <input type=\"email\" value=\"{{ last_username }}\" name=\"_username\" id=\"username\" 
                                               class=\"form-control\" placeholder=\"jhon@example.com\" autofocus>
                                    </div>
                                    
                                    <div class=\"mb-3\">
                                        <label for=\"password\" class=\"form-label\">Password</label>
                                        <div class=\"input-group\">
                                            <input type=\"password\" name=\"_password\" id=\"password\" 
                                                   class=\"form-control\" placeholder=\"Enter Password\">
                                            <button class=\"btn btn-outline-secondary\" type=\"button\" id=\"togglePassword\">
                                                <i class=\"fas fa-eye\"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class=\"d-flex justify-content-between align-items-center mb-4\">
                                        <div class=\"form-check\">
                                            <input class=\"form-check-input\" type=\"checkbox\" id=\"remember_me\" name=\"_remember_me\">
                                            <label class=\"form-check-label\" for=\"remember_me\">
                                                Remember Me
                                            </label>
                                        </div>
                                        <a href=\"#\" class=\"forgot-link\">Forgot Password?</a>
                                    </div>
                                    
                                    <input type=\"hidden\" name=\"_csrf_token\" value=\"{{ csrf_token('authenticate') }}\">
                                    
                                    <button type=\"submit\" class=\"btn btn-login mb-3\">Login</button>
                                    
                                    <div class=\"register-link d-lg-none\">
                                        <p>Don't have an account yet? <a href=\"{{ path('app_register') }}\">Sign up here</a></p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src=\"https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js\"></script>
    
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
        
        // Handle form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            const email = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            
            if (!email || !password) {
                e.preventDefault();
                alert('Please fill in all fields');
            }
        });
        
        // Disable HTML5 validation for form
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            if (form) {
                form.setAttribute('novalidate', 'novalidate');
                
                // Prevent default validation on submit
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                }, false);
                
                console.log('HTML5 validation disabled for login form');
            }
        });
    </script>
</body>
</html>", "security/login_old.html.twig", "C:\\Users\\rajhi\\pidev-education-app\\templates\\security\\login_old.html.twig");
    }
}
