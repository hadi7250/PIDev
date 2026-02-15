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

/* security/register_old.html.twig */
class __TwigTemplate_febb7e7d8e5faf8ac04a4b9569cf2c09 extends Template
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
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "security/register_old.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "security/register_old.html.twig"));

        // line 1
        yield "<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>PIDEV Education - Create Account</title>
    
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
        
        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .register-left {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .register-right {
            padding: 50px;
        }
        
        .brand-logo {
            width: 150px;
            margin-bottom: 30px;
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
        
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            width: 100%;
        }
        
        .btn-register:hover {
            opacity: 0.9;
            color: white;
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        
        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        .form-check-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        }
        
        .password-toggle {
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }
        
        .password-input-group {
            position: relative;
        }
        
        @media (max-width: 768px) {
            .register-left {
                padding: 30px;
            }
            .register-right {
                padding: 30px;
            }
        }
        
        .feature-icon {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .step-indicator {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .step {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #e0e0e0;
            color: #666;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        .step.active {
            background: #667eea;
            color: white;
        }
        
        .step-line {
            flex: 1;
            height: 2px;
            background: #e0e0e0;
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <div class=\"container\">
        <div class=\"row justify-content-center\">
            <div class=\"col-lg-10\">
                <div class=\"register-card\">
                    <div class=\"row g-0\">
                        <!-- Left Side - Branding & Info -->
                        <div class=\"col-lg-6 d-none d-lg-block\">
                            <div class=\"register-left\">
                                <div class=\"text-center mb-4\">
                                    <h1 class=\"display-5 fw-bold\">PIDEV Education</h1>
                                    <p class=\"lead\">Join Our Community</p>
                                </div>
                                
                                <div class=\"step-indicator justify-content-center mb-5\">
                                    <div class=\"step active\">1</div>
                                    <div class=\"step-line\"></div>
                                    <div class=\"step\">2</div>
                                    <div class=\"step-line\"></div>
                                    <div class=\"step\">3</div>
                                </div>
                                
                                <div class=\"features\">
                                    <div class=\"d-flex align-items-center mb-4\">
                                        <div class=\"feature-icon me-3\">
                                            <i class=\"fas fa-lightbulb fa-2x\"></i>
                                        </div>
                                        <div>
                                            <h5>Pitch Your Ideas</h5>
                                            <p class=\"mb-0\">Present to real investors in live sessions</p>
                                        </div>
                                    </div>
                                    
                                    <div class=\"d-flex align-items-center mb-4\">
                                        <div class=\"feature-icon me-3\">
                                            <i class=\"fas fa-book-open fa-2x\"></i>
                                        </div>
                                        <div>
                                            <h5>Learn from Experts</h5>
                                            <p class=\"mb-0\">Access premium courses and resources</p>
                                        </div>
                                    </div>
                                    
                                    <div class=\"d-flex align-items-center\">
                                        <div class=\"feature-icon me-3\">
                                            <i class=\"fas fa-handshake fa-2x\"></i>
                                        </div>
                                        <div>
                                            <h5>Build Connections</h5>
                                            <p class=\"mb-0\">Network with entrepreneurs and mentors</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class=\"mt-5 text-center\">
                                    <p>Already have an account?</p>
                                    <a href=\"";
        // line 218
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_login");
        yield "\" class=\"btn btn-outline-light btn-lg px-4\">
                                        Sign In
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Side - Registration Form -->
                        <div class=\"col-lg-6\">
                            <div class=\"register-right\">
                                <div class=\"text-center mb-4 d-lg-none\">
                                    <h1 class=\"fw-bold\" style=\"color: #667eea;\">PIDEV Education</h1>
                                    <p class=\"text-muted\">Create Your Account</p>
                                </div>
                                
                                <h2 class=\"fw-bold mb-1\">Create Account</h2>
                                <p class=\"text-muted mb-4\">Join our community of entrepreneurs, investors, and students</p>
                                
                                <!-- Registration Form -->
                                ";
        // line 237
        yield         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["registrationForm"]) || array_key_exists("registrationForm", $context) ? $context["registrationForm"] : (function () { throw new RuntimeError('Variable "registrationForm" does not exist.', 237, $this->source); })()), 'form_start', ["attr" => ["novalidate" => "novalidate"]]);
        yield "
                                
                                    ";
        // line 239
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 239, $this->source); })()), "flashes", ["success"], "method", false, false, false, 239));
        foreach ($context['_seq'] as $context["_key"] => $context["flash"]) {
            // line 240
            yield "                                        <div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\">
                                            ";
            // line 241
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["flash"], "html", null, true);
            yield "
                                            <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\"></button>
                                        </div>
                                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['flash'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 245
        yield "                                    
                                    <div class=\"row\">
                                        <!-- NSC Field -->
                                        <div class=\"col-md-6 mb-3\">
                                            ";
        // line 249
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["registrationForm"]) || array_key_exists("registrationForm", $context) ? $context["registrationForm"] : (function () { throw new RuntimeError('Variable "registrationForm" does not exist.', 249, $this->source); })()), "nsc", [], "any", false, false, false, 249), 'label', ["label_attr" => ["class" => "form-label"], "label" => "NSC (Numéro Étudiant)"]);
        yield "
                                            ";
        // line 250
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["registrationForm"]) || array_key_exists("registrationForm", $context) ? $context["registrationForm"] : (function () { throw new RuntimeError('Variable "registrationForm" does not exist.', 250, $this->source); })()), "nsc", [], "any", false, false, false, 250), 'widget', ["attr" => ["class" => "form-control", "placeholder" => "123456"]]);
        // line 255
        yield "
                                            ";
        // line 256
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["registrationForm"]) || array_key_exists("registrationForm", $context) ? $context["registrationForm"] : (function () { throw new RuntimeError('Variable "registrationForm" does not exist.', 256, $this->source); })()), "nsc", [], "any", false, false, false, 256), 'errors');
        yield "
                                        </div>
                                        
                                        <!-- Email Field -->
                                        <div class=\"col-md-6 mb-3\">
                                            ";
        // line 261
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["registrationForm"]) || array_key_exists("registrationForm", $context) ? $context["registrationForm"] : (function () { throw new RuntimeError('Variable "registrationForm" does not exist.', 261, $this->source); })()), "email", [], "any", false, false, false, 261), 'label', ["label_attr" => ["class" => "form-label"], "label" => "Email"]);
        yield "
                                            ";
        // line 262
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["registrationForm"]) || array_key_exists("registrationForm", $context) ? $context["registrationForm"] : (function () { throw new RuntimeError('Variable "registrationForm" does not exist.', 262, $this->source); })()), "email", [], "any", false, false, false, 262), 'widget', ["attr" => ["class" => "form-control", "placeholder" => "example@esprit.tn"]]);
        // line 267
        yield "
                                            ";
        // line 268
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["registrationForm"]) || array_key_exists("registrationForm", $context) ? $context["registrationForm"] : (function () { throw new RuntimeError('Variable "registrationForm" does not exist.', 268, $this->source); })()), "email", [], "any", false, false, false, 268), 'errors');
        yield "
                                        </div>
                                    </div>
                                    
                                    <!-- Name Field -->
                                    <div class=\"mb-3\">
                                        ";
        // line 274
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["registrationForm"]) || array_key_exists("registrationForm", $context) ? $context["registrationForm"] : (function () { throw new RuntimeError('Variable "registrationForm" does not exist.', 274, $this->source); })()), "name", [], "any", false, false, false, 274), 'label', ["label_attr" => ["class" => "form-label"], "label" => "Nom Complet"]);
        yield "
                                        ";
        // line 275
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["registrationForm"]) || array_key_exists("registrationForm", $context) ? $context["registrationForm"] : (function () { throw new RuntimeError('Variable "registrationForm" does not exist.', 275, $this->source); })()), "name", [], "any", false, false, false, 275), 'widget', ["attr" => ["class" => "form-control", "placeholder" => "John Doe"]]);
        // line 280
        yield "
                                        ";
        // line 281
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["registrationForm"]) || array_key_exists("registrationForm", $context) ? $context["registrationForm"] : (function () { throw new RuntimeError('Variable "registrationForm" does not exist.', 281, $this->source); })()), "name", [], "any", false, false, false, 281), 'errors');
        yield "
                                    </div>
                                    
                                    <!-- Password Field -->
                                    <div class=\"mb-3\">
                                        ";
        // line 286
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["registrationForm"]) || array_key_exists("registrationForm", $context) ? $context["registrationForm"] : (function () { throw new RuntimeError('Variable "registrationForm" does not exist.', 286, $this->source); })()), "plainPassword", [], "any", false, false, false, 286), "first", [], "any", false, false, false, 286), 'label', ["label_attr" => ["class" => "form-label"], "label" => "Mot de passe"]);
        yield "
                                        <div class=\"password-input-group\">
                                            ";
        // line 288
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["registrationForm"]) || array_key_exists("registrationForm", $context) ? $context["registrationForm"] : (function () { throw new RuntimeError('Variable "registrationForm" does not exist.', 288, $this->source); })()), "plainPassword", [], "any", false, false, false, 288), "first", [], "any", false, false, false, 288), 'widget', ["attr" => ["class" => "form-control", "placeholder" => "Minimum 6 characters"]]);
        // line 293
        yield "
                                            <span class=\"password-toggle\" onclick=\"togglePassword('first')\">
                                                <i class=\"fas fa-eye\"></i>
                                            </span>
                                        </div>
                                        ";
        // line 298
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["registrationForm"]) || array_key_exists("registrationForm", $context) ? $context["registrationForm"] : (function () { throw new RuntimeError('Variable "registrationForm" does not exist.', 298, $this->source); })()), "plainPassword", [], "any", false, false, false, 298), "first", [], "any", false, false, false, 298), 'errors');
        yield "
                                    </div>
                                    
                                    <!-- Repeat Password Field -->
                                    <div class=\"mb-3\">
                                        ";
        // line 303
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["registrationForm"]) || array_key_exists("registrationForm", $context) ? $context["registrationForm"] : (function () { throw new RuntimeError('Variable "registrationForm" does not exist.', 303, $this->source); })()), "plainPassword", [], "any", false, false, false, 303), "second", [], "any", false, false, false, 303), 'label', ["label_attr" => ["class" => "form-label"], "label" => "Répéter le mot de passe"]);
        yield "
                                        <div class=\"password-input-group\">
                                            ";
        // line 305
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["registrationForm"]) || array_key_exists("registrationForm", $context) ? $context["registrationForm"] : (function () { throw new RuntimeError('Variable "registrationForm" does not exist.', 305, $this->source); })()), "plainPassword", [], "any", false, false, false, 305), "second", [], "any", false, false, false, 305), 'widget', ["attr" => ["class" => "form-control", "placeholder" => "Repeat password"]]);
        // line 310
        yield "
                                            <span class=\"password-toggle\" onclick=\"togglePassword('second')\">
                                                <i class=\"fas fa-eye\"></i>
                                            </span>
                                        </div>
                                        ";
        // line 315
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["registrationForm"]) || array_key_exists("registrationForm", $context) ? $context["registrationForm"] : (function () { throw new RuntimeError('Variable "registrationForm" does not exist.', 315, $this->source); })()), "plainPassword", [], "any", false, false, false, 315), "second", [], "any", false, false, false, 315), 'errors');
        yield "
                                    </div>
                                    
                                    <!-- Terms Agreement -->
                                    <div class=\"mb-4\">
                                        <div class=\"form-check\">
                                            ";
        // line 321
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["registrationForm"]) || array_key_exists("registrationForm", $context) ? $context["registrationForm"] : (function () { throw new RuntimeError('Variable "registrationForm" does not exist.', 321, $this->source); })()), "agreeTerms", [], "any", false, false, false, 321), 'widget', ["attr" => ["class" => "form-check-input"]]);
        // line 325
        yield "
                                            ";
        // line 326
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["registrationForm"]) || array_key_exists("registrationForm", $context) ? $context["registrationForm"] : (function () { throw new RuntimeError('Variable "registrationForm" does not exist.', 326, $this->source); })()), "agreeTerms", [], "any", false, false, false, 326), 'label', ["label_attr" => ["class" => "form-check-label"], "label" => "J'accepte les conditions d'utilisation"]);
        // line 330
        yield "
                                        </div>
                                        ";
        // line 332
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["registrationForm"]) || array_key_exists("registrationForm", $context) ? $context["registrationForm"] : (function () { throw new RuntimeError('Variable "registrationForm" does not exist.', 332, $this->source); })()), "agreeTerms", [], "any", false, false, false, 332), 'errors');
        yield "
                                    </div>
                                    
                                    <!-- Submit Button -->
                                    <button type=\"submit\" class=\"btn btn-register mb-3\">Create Account</button>
                                    
                                    <div class=\"login-link d-lg-none\">
                                        <p>Already have an account? <a href=\"";
        // line 339
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_login");
        yield "\">Login here</a></p>
                                    </div>
                                
                                ";
        // line 342
        yield         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["registrationForm"]) || array_key_exists("registrationForm", $context) ? $context["registrationForm"] : (function () { throw new RuntimeError('Variable "registrationForm" does not exist.', 342, $this->source); })()), 'form_end');
        yield "
                                
                                <!-- Account Types Info -->
                                <div class=\"mt-4 pt-3 border-top\">
                                    <h6 class=\"fw-bold mb-3\">Choose Your Path:</h6>
                                    <div class=\"row\">
                                        <div class=\"col-4 text-center\">
                                            <div class=\"p-2 rounded\" style=\"background: #e3f2fd;\">
                                                <i class=\"fas fa-user-graduate text-primary\"></i>
                                                <p class=\"mb-0 small mt-1\">Student</p>
                                            </div>
                                        </div>
                                        <div class=\"col-4 text-center\">
                                            <div class=\"p-2 rounded\" style=\"background: #f3e5f5;\">
                                                <i class=\"fas fa-briefcase text-purple\"></i>
                                                <p class=\"mb-0 small mt-1\">Entrepreneur</p>
                                            </div>
                                        </div>
                                        <div class=\"col-4 text-center\">
                                            <div class=\"p-2 rounded\" style=\"background: #e8f5e9;\">
                                                <i class=\"fas fa-chart-line text-success\"></i>
                                                <p class=\"mb-0 small mt-1\">Investor</p>
                                            </div>
                                        </div>
                                    </div>
                                    <p class=\"text-muted small mt-3\">All new accounts start as Students. You can upgrade your role later.</p>
                                </div>
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
        function togglePassword(type) {
            const inputId = 'registration_form_plainPassword_' + type;
            const passwordInput = document.getElementById(inputId);
            const icon = document.querySelector('[onclick=\"togglePassword(\\'' + type + '\\')\"] i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
        
        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const password1 = document.getElementById('registration_form_plainPassword_first').value;
            const password2 = document.getElementById('registration_form_plainPassword_second').value;
            const terms = document.getElementById('registration_form_agreeTerms');
            
            // Check if passwords match
            if (password1 !== password2) {
                e.preventDefault();
                alert('Passwords do not match!');
                return;
            }
            
            // Check password length
            if (password1.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters long!');
                return;
            }
            
            // Check terms agreement
            if (!terms.checked) {
                e.preventDefault();
                alert('You must agree to the terms and conditions!');
                return;
            }
        });
        
        // Auto-format NSC input
        document.getElementById('registration_form_nsc').addEventListener('input', function(e) {
            this.value = this.value.replace(/\\D/g, '');
        });
        
        // Real-time password strength indicator
        const passwordInput = document.getElementById('registration_form_plainPassword_first');
        passwordInput.addEventListener('input', function() {
            const strengthMeter = document.getElementById('password-strength');
            if (!strengthMeter) return;
            
            const password = this.value;
            let strength = 0;
            
            if (password.length >= 6) strength++;
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            const strengthText = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong', 'Very Strong'];
            strengthMeter.textContent = strengthText[Math.min(strength, 5)];
            
            const colors = ['#dc3545', '#ffc107', '#ffc107', '#28a745', '#28a745', '#20c997'];
            strengthMeter.style.color = colors[Math.min(strength, 5)];
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
                
                console.log('HTML5 validation disabled for registration form');
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
        return "security/register_old.html.twig";
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
        return array (  441 => 342,  435 => 339,  425 => 332,  421 => 330,  419 => 326,  416 => 325,  414 => 321,  405 => 315,  398 => 310,  396 => 305,  391 => 303,  383 => 298,  376 => 293,  374 => 288,  369 => 286,  361 => 281,  358 => 280,  356 => 275,  352 => 274,  343 => 268,  340 => 267,  338 => 262,  334 => 261,  326 => 256,  323 => 255,  321 => 250,  317 => 249,  311 => 245,  301 => 241,  298 => 240,  294 => 239,  289 => 237,  267 => 218,  48 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>PIDEV Education - Create Account</title>
    
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
        
        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .register-left {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .register-right {
            padding: 50px;
        }
        
        .brand-logo {
            width: 150px;
            margin-bottom: 30px;
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
        
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            width: 100%;
        }
        
        .btn-register:hover {
            opacity: 0.9;
            color: white;
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        
        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        .form-check-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        }
        
        .password-toggle {
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }
        
        .password-input-group {
            position: relative;
        }
        
        @media (max-width: 768px) {
            .register-left {
                padding: 30px;
            }
            .register-right {
                padding: 30px;
            }
        }
        
        .feature-icon {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .step-indicator {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .step {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #e0e0e0;
            color: #666;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        .step.active {
            background: #667eea;
            color: white;
        }
        
        .step-line {
            flex: 1;
            height: 2px;
            background: #e0e0e0;
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <div class=\"container\">
        <div class=\"row justify-content-center\">
            <div class=\"col-lg-10\">
                <div class=\"register-card\">
                    <div class=\"row g-0\">
                        <!-- Left Side - Branding & Info -->
                        <div class=\"col-lg-6 d-none d-lg-block\">
                            <div class=\"register-left\">
                                <div class=\"text-center mb-4\">
                                    <h1 class=\"display-5 fw-bold\">PIDEV Education</h1>
                                    <p class=\"lead\">Join Our Community</p>
                                </div>
                                
                                <div class=\"step-indicator justify-content-center mb-5\">
                                    <div class=\"step active\">1</div>
                                    <div class=\"step-line\"></div>
                                    <div class=\"step\">2</div>
                                    <div class=\"step-line\"></div>
                                    <div class=\"step\">3</div>
                                </div>
                                
                                <div class=\"features\">
                                    <div class=\"d-flex align-items-center mb-4\">
                                        <div class=\"feature-icon me-3\">
                                            <i class=\"fas fa-lightbulb fa-2x\"></i>
                                        </div>
                                        <div>
                                            <h5>Pitch Your Ideas</h5>
                                            <p class=\"mb-0\">Present to real investors in live sessions</p>
                                        </div>
                                    </div>
                                    
                                    <div class=\"d-flex align-items-center mb-4\">
                                        <div class=\"feature-icon me-3\">
                                            <i class=\"fas fa-book-open fa-2x\"></i>
                                        </div>
                                        <div>
                                            <h5>Learn from Experts</h5>
                                            <p class=\"mb-0\">Access premium courses and resources</p>
                                        </div>
                                    </div>
                                    
                                    <div class=\"d-flex align-items-center\">
                                        <div class=\"feature-icon me-3\">
                                            <i class=\"fas fa-handshake fa-2x\"></i>
                                        </div>
                                        <div>
                                            <h5>Build Connections</h5>
                                            <p class=\"mb-0\">Network with entrepreneurs and mentors</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class=\"mt-5 text-center\">
                                    <p>Already have an account?</p>
                                    <a href=\"{{ path('app_login') }}\" class=\"btn btn-outline-light btn-lg px-4\">
                                        Sign In
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Side - Registration Form -->
                        <div class=\"col-lg-6\">
                            <div class=\"register-right\">
                                <div class=\"text-center mb-4 d-lg-none\">
                                    <h1 class=\"fw-bold\" style=\"color: #667eea;\">PIDEV Education</h1>
                                    <p class=\"text-muted\">Create Your Account</p>
                                </div>
                                
                                <h2 class=\"fw-bold mb-1\">Create Account</h2>
                                <p class=\"text-muted mb-4\">Join our community of entrepreneurs, investors, and students</p>
                                
                                <!-- Registration Form -->
                                {{ form_start(registrationForm, {attr: {novalidate: 'novalidate'}}) }}
                                
                                    {% for flash in app.flashes('success') %}
                                        <div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\">
                                            {{ flash }}
                                            <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\"></button>
                                        </div>
                                    {% endfor %}
                                    
                                    <div class=\"row\">
                                        <!-- NSC Field -->
                                        <div class=\"col-md-6 mb-3\">
                                            {{ form_label(registrationForm.nsc, 'NSC (Numéro Étudiant)', {'label_attr': {'class': 'form-label'}}) }}
                                            {{ form_widget(registrationForm.nsc, {
                                                'attr': {
                                                    'class': 'form-control',
                                                    'placeholder': '123456'
                                                }
                                            }) }}
                                            {{ form_errors(registrationForm.nsc) }}
                                        </div>
                                        
                                        <!-- Email Field -->
                                        <div class=\"col-md-6 mb-3\">
                                            {{ form_label(registrationForm.email, 'Email', {'label_attr': {'class': 'form-label'}}) }}
                                            {{ form_widget(registrationForm.email, {
                                                'attr': {
                                                    'class': 'form-control',
                                                    'placeholder': 'example@esprit.tn'
                                                }
                                            }) }}
                                            {{ form_errors(registrationForm.email) }}
                                        </div>
                                    </div>
                                    
                                    <!-- Name Field -->
                                    <div class=\"mb-3\">
                                        {{ form_label(registrationForm.name, 'Nom Complet', {'label_attr': {'class': 'form-label'}}) }}
                                        {{ form_widget(registrationForm.name, {
                                            'attr': {
                                                'class': 'form-control',
                                                'placeholder': 'John Doe'
                                            }
                                        }) }}
                                        {{ form_errors(registrationForm.name) }}
                                    </div>
                                    
                                    <!-- Password Field -->
                                    <div class=\"mb-3\">
                                        {{ form_label(registrationForm.plainPassword.first, 'Mot de passe', {'label_attr': {'class': 'form-label'}}) }}
                                        <div class=\"password-input-group\">
                                            {{ form_widget(registrationForm.plainPassword.first, {
                                                'attr': {
                                                    'class': 'form-control',
                                                    'placeholder': 'Minimum 6 characters'
                                                }
                                            }) }}
                                            <span class=\"password-toggle\" onclick=\"togglePassword('first')\">
                                                <i class=\"fas fa-eye\"></i>
                                            </span>
                                        </div>
                                        {{ form_errors(registrationForm.plainPassword.first) }}
                                    </div>
                                    
                                    <!-- Repeat Password Field -->
                                    <div class=\"mb-3\">
                                        {{ form_label(registrationForm.plainPassword.second, 'Répéter le mot de passe', {'label_attr': {'class': 'form-label'}}) }}
                                        <div class=\"password-input-group\">
                                            {{ form_widget(registrationForm.plainPassword.second, {
                                                'attr': {
                                                    'class': 'form-control',
                                                    'placeholder': 'Repeat password'
                                                }
                                            }) }}
                                            <span class=\"password-toggle\" onclick=\"togglePassword('second')\">
                                                <i class=\"fas fa-eye\"></i>
                                            </span>
                                        </div>
                                        {{ form_errors(registrationForm.plainPassword.second) }}
                                    </div>
                                    
                                    <!-- Terms Agreement -->
                                    <div class=\"mb-4\">
                                        <div class=\"form-check\">
                                            {{ form_widget(registrationForm.agreeTerms, {
                                                'attr': {
                                                    'class': 'form-check-input'
                                                }
                                            }) }}
                                            {{ form_label(registrationForm.agreeTerms, 'J\\'accepte les conditions d\\'utilisation', {
                                                'label_attr': {
                                                    'class': 'form-check-label'
                                                }
                                            }) }}
                                        </div>
                                        {{ form_errors(registrationForm.agreeTerms) }}
                                    </div>
                                    
                                    <!-- Submit Button -->
                                    <button type=\"submit\" class=\"btn btn-register mb-3\">Create Account</button>
                                    
                                    <div class=\"login-link d-lg-none\">
                                        <p>Already have an account? <a href=\"{{ path('app_login') }}\">Login here</a></p>
                                    </div>
                                
                                {{ form_end(registrationForm) }}
                                
                                <!-- Account Types Info -->
                                <div class=\"mt-4 pt-3 border-top\">
                                    <h6 class=\"fw-bold mb-3\">Choose Your Path:</h6>
                                    <div class=\"row\">
                                        <div class=\"col-4 text-center\">
                                            <div class=\"p-2 rounded\" style=\"background: #e3f2fd;\">
                                                <i class=\"fas fa-user-graduate text-primary\"></i>
                                                <p class=\"mb-0 small mt-1\">Student</p>
                                            </div>
                                        </div>
                                        <div class=\"col-4 text-center\">
                                            <div class=\"p-2 rounded\" style=\"background: #f3e5f5;\">
                                                <i class=\"fas fa-briefcase text-purple\"></i>
                                                <p class=\"mb-0 small mt-1\">Entrepreneur</p>
                                            </div>
                                        </div>
                                        <div class=\"col-4 text-center\">
                                            <div class=\"p-2 rounded\" style=\"background: #e8f5e9;\">
                                                <i class=\"fas fa-chart-line text-success\"></i>
                                                <p class=\"mb-0 small mt-1\">Investor</p>
                                            </div>
                                        </div>
                                    </div>
                                    <p class=\"text-muted small mt-3\">All new accounts start as Students. You can upgrade your role later.</p>
                                </div>
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
        function togglePassword(type) {
            const inputId = 'registration_form_plainPassword_' + type;
            const passwordInput = document.getElementById(inputId);
            const icon = document.querySelector('[onclick=\"togglePassword(\\'' + type + '\\')\"] i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
        
        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const password1 = document.getElementById('registration_form_plainPassword_first').value;
            const password2 = document.getElementById('registration_form_plainPassword_second').value;
            const terms = document.getElementById('registration_form_agreeTerms');
            
            // Check if passwords match
            if (password1 !== password2) {
                e.preventDefault();
                alert('Passwords do not match!');
                return;
            }
            
            // Check password length
            if (password1.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters long!');
                return;
            }
            
            // Check terms agreement
            if (!terms.checked) {
                e.preventDefault();
                alert('You must agree to the terms and conditions!');
                return;
            }
        });
        
        // Auto-format NSC input
        document.getElementById('registration_form_nsc').addEventListener('input', function(e) {
            this.value = this.value.replace(/\\D/g, '');
        });
        
        // Real-time password strength indicator
        const passwordInput = document.getElementById('registration_form_plainPassword_first');
        passwordInput.addEventListener('input', function() {
            const strengthMeter = document.getElementById('password-strength');
            if (!strengthMeter) return;
            
            const password = this.value;
            let strength = 0;
            
            if (password.length >= 6) strength++;
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            const strengthText = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong', 'Very Strong'];
            strengthMeter.textContent = strengthText[Math.min(strength, 5)];
            
            const colors = ['#dc3545', '#ffc107', '#ffc107', '#28a745', '#28a745', '#20c997'];
            strengthMeter.style.color = colors[Math.min(strength, 5)];
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
                
                console.log('HTML5 validation disabled for registration form');
            }
        });
    </script>
</body>
</html>", "security/register_old.html.twig", "C:\\Users\\rajhi\\pidev-education-app\\templates\\security\\register_old.html.twig");
    }
}
