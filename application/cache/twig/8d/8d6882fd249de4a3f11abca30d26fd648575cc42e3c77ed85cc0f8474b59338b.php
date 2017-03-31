<?php

/* login/login_v.twig */
class __TwigTemplate_8f00542a6b4f73a92da384375a31f0321dcd0a478ec66a0864ca68bc5eae0abb extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<section class=\"content\">
    <div class=\"back-link\">
        <a class=\"btn btn-accent\" href=\"";
        // line 3
        echo twig_escape_filter($this->env, base_url(), "html", null, true);
        echo "\">
            Back to main page
        </a>
    </div>
    <div class=\"container-center animated slideInDown\">
        <div class=\"view-header\">
            <div class=\"header-icon\">
                <i class=\"pe page-header-icon pe-7s-unlock\">
                </i>
            </div>
            <div class=\"header-title\">
                <h3>
                    Login
                </h3>
                <small>
                    Please enter your Eve Trade Master credentials to login.
                </small>
            </div>
        </div>
        <div class=\"panel panel-filled\">
            <div class=\"panel-body panel-login\">
                <form action=\"";
        // line 24
        echo twig_escape_filter($this->env, base_url("login/process"), "html", null, true);
        echo "\" id=\"loginForm\" method=\"POST\" name=\"login\">
                    <div class=\"form-group\">
                        <label class=\"control-label\" for=\"username\">
                            Username
                        </label>
                        <input class=\"form-control\" id=\"username\" name=\"username\" required=\"\" title=\"Please enter you username\" type=\"text\">
                            <span class=\"help-block small\">
                                Your unique username to Eve Trade Master
                            </span>
                    </div>
                    <div class=\"form-group\">
                        <label class=\"control-label\" for=\"password\">
                            Password
                        </label>
                        <input class=\"form-control\" id=\"password\" name=\"password\" required=\"\" title=\"Please enter your password\" type=\"password\">
                            <span class=\"help-block small\">
                                Your strong password
                            </span>
                    </div>
                    <div class=\"text-center\">
                        <input class=\"btn btn-default\" id=\"login-btn\" name=\"Login\" type=\"Submit\" value=\"Login\">
                            <a class=\"btn btn-default\" href=\"";
        // line 45
        echo twig_escape_filter($this->env, base_url("main/register"), "html", null, true);
        echo "\">
                                Register
                            </a>
                    </div>
                    <span class=\"help-block small text-center help-forgot\">
                        <a href=\"";
        // line 50
        echo twig_escape_filter($this->env, base_url("recovery/index/username"), "html", null, true);
        echo "\">Forgot username</a> | 
                        <a href=\"";
        // line 51
        echo twig_escape_filter($this->env, base_url("recovery/index/password"), "html", null, true);
        echo "\">Forgot password</a>
                    </span>
                </form>
            </div>
            <div class=\"panel-body panel-loading\">
                Logging in... updating data
                <div class=\"windows8\">
                    <br>
                    <div class=\"wBall\" class=\"wBall_1\">
                        <div class=\"wInnerBall\">
                        </div>
                    </div>
                    <div class=\"wBall\" class=\"wBall_2\">
                        <div class=\"wInnerBall\">
                        </div>
                    </div>
                    <div class=\"wBall\" class=\"wBall_3\">
                        <div class=\"wInnerBall\">
                        </div>
                    </div>
                    <div class=\"wBall\" class=\"wBall_4\">
                        <div class=\"wInnerBall\">
                        </div>
                    </div>
                    <div class=\"wBall\" class=\"wBall_5\">
                        <div class=\"wInnerBall\">
                        </div>
                    </div>
                </div>
                <br>
                <br>
            </div>
        </div>
    </div>
</section>
<script src=\"";
        // line 86
        echo twig_escape_filter($this->env, base_url("dist/js/apps/login.js"), "html", null, true);
        echo "?HASH_CACHE=";
        echo twig_escape_filter($this->env, ($context["HASH_CACHE"] ?? null), "html", null, true);
        echo "\"></script>";
    }

    public function getTemplateName()
    {
        return "login/login_v.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  121 => 86,  83 => 51,  79 => 50,  71 => 45,  47 => 24,  23 => 3,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("<section class=\"content\">
    <div class=\"back-link\">
        <a class=\"btn btn-accent\" href=\"{{ base_url() }}\">
            Back to main page
        </a>
    </div>
    <div class=\"container-center animated slideInDown\">
        <div class=\"view-header\">
            <div class=\"header-icon\">
                <i class=\"pe page-header-icon pe-7s-unlock\">
                </i>
            </div>
            <div class=\"header-title\">
                <h3>
                    Login
                </h3>
                <small>
                    Please enter your Eve Trade Master credentials to login.
                </small>
            </div>
        </div>
        <div class=\"panel panel-filled\">
            <div class=\"panel-body panel-login\">
                <form action=\"{{ base_url('login/process') }}\" id=\"loginForm\" method=\"POST\" name=\"login\">
                    <div class=\"form-group\">
                        <label class=\"control-label\" for=\"username\">
                            Username
                        </label>
                        <input class=\"form-control\" id=\"username\" name=\"username\" required=\"\" title=\"Please enter you username\" type=\"text\">
                            <span class=\"help-block small\">
                                Your unique username to Eve Trade Master
                            </span>
                    </div>
                    <div class=\"form-group\">
                        <label class=\"control-label\" for=\"password\">
                            Password
                        </label>
                        <input class=\"form-control\" id=\"password\" name=\"password\" required=\"\" title=\"Please enter your password\" type=\"password\">
                            <span class=\"help-block small\">
                                Your strong password
                            </span>
                    </div>
                    <div class=\"text-center\">
                        <input class=\"btn btn-default\" id=\"login-btn\" name=\"Login\" type=\"Submit\" value=\"Login\">
                            <a class=\"btn btn-default\" href=\"{{ base_url('main/register') }}\">
                                Register
                            </a>
                    </div>
                    <span class=\"help-block small text-center help-forgot\">
                        <a href=\"{{ base_url('recovery/index/username') }}\">Forgot username</a> | 
                        <a href=\"{{ base_url('recovery/index/password') }}\">Forgot password</a>
                    </span>
                </form>
            </div>
            <div class=\"panel-body panel-loading\">
                Logging in... updating data
                <div class=\"windows8\">
                    <br>
                    <div class=\"wBall\" class=\"wBall_1\">
                        <div class=\"wInnerBall\">
                        </div>
                    </div>
                    <div class=\"wBall\" class=\"wBall_2\">
                        <div class=\"wInnerBall\">
                        </div>
                    </div>
                    <div class=\"wBall\" class=\"wBall_3\">
                        <div class=\"wInnerBall\">
                        </div>
                    </div>
                    <div class=\"wBall\" class=\"wBall_4\">
                        <div class=\"wInnerBall\">
                        </div>
                    </div>
                    <div class=\"wBall\" class=\"wBall_5\">
                        <div class=\"wInnerBall\">
                        </div>
                    </div>
                </div>
                <br>
                <br>
            </div>
        </div>
    </div>
</section>
<script src=\"{{ base_url('dist/js/apps/login.js') }}?HASH_CACHE={{HASH_CACHE}}\"></script>", "login/login_v.twig", "C:\\xampp\\htdocs\\v2\\application\\views\\login\\login_v.twig");
    }
}
