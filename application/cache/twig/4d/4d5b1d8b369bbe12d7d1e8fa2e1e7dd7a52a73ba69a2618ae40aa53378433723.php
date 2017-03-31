<?php

/* recovery/recover_username_v.twig */
class __TwigTemplate_5d810e4d810a1fb718234c4dc24306d91d8343dac3d3293a4cae9537980eab96 extends Twig_Template
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
        echo "<!-- Wrapper-->
<script src=\"<?=base_url('dist/js/apps/recovery-app.js')?>?HASH_CACHE=<?=HASH_CACHE?>\"></script>
<div class=\"wrapper\">
    <!-- Main content-->
    <section class=\"content\">
        <div class=\"back-link\">
            <a onclick=\"window.history.back()\" class=\"btn btn-accent go-back\">Go back</a>
        </div>
        <div class=\"container-center animated slideInDown\">
            <div class=\"view-header\">
                <div class=\"header-icon\">
                    <i class=\"pe page-header-icon pe-7s-id\"></i>
                </div>
                <div class=\"header-title\">
                    <h3>Forgot username?</h3>
                    <small>
                        No problem! Just fill in the e-mail associated with your account to proceed
                    </small>
                </div>
            </div>

            <div class=\"panel panel-filled\">
                <div class=\"panel-body\">
                    <form method=\"POST\" id=\"recovery\" novalidate>
                        <div class=\"form-group\">
                            <label class=\"control-label\" for=\"email\">Email</label>
                            <input type=\"text\" placeholder=\"Please enter your e-mail\" name=\"email\" id=\"email\" class=\"form-control\" required autofocus>
                        </div>
                        <div>
                            <button class=\"btn btn-accent forgot-username\">Submit</button>
                            <a class=\"btn btn-default\" href=\"<?=base_url('main')?>\">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </section>
    <!-- End main content-->
</div>
<!-- End wrapper-->";
    }

    public function getTemplateName()
    {
        return "recovery/recover_username_v.twig";
    }

    public function getDebugInfo()
    {
        return array (  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("<!-- Wrapper-->
<script src=\"<?=base_url('dist/js/apps/recovery-app.js')?>?HASH_CACHE=<?=HASH_CACHE?>\"></script>
<div class=\"wrapper\">
    <!-- Main content-->
    <section class=\"content\">
        <div class=\"back-link\">
            <a onclick=\"window.history.back()\" class=\"btn btn-accent go-back\">Go back</a>
        </div>
        <div class=\"container-center animated slideInDown\">
            <div class=\"view-header\">
                <div class=\"header-icon\">
                    <i class=\"pe page-header-icon pe-7s-id\"></i>
                </div>
                <div class=\"header-title\">
                    <h3>Forgot username?</h3>
                    <small>
                        No problem! Just fill in the e-mail associated with your account to proceed
                    </small>
                </div>
            </div>

            <div class=\"panel panel-filled\">
                <div class=\"panel-body\">
                    <form method=\"POST\" id=\"recovery\" novalidate>
                        <div class=\"form-group\">
                            <label class=\"control-label\" for=\"email\">Email</label>
                            <input type=\"text\" placeholder=\"Please enter your e-mail\" name=\"email\" id=\"email\" class=\"form-control\" required autofocus>
                        </div>
                        <div>
                            <button class=\"btn btn-accent forgot-username\">Submit</button>
                            <a class=\"btn btn-default\" href=\"<?=base_url('main')?>\">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </section>
    <!-- End main content-->
</div>
<!-- End wrapper-->", "recovery/recover_username_v.twig", "C:\\xampp\\htdocs\\v2\\application\\views\\recovery\\recover_username_v.twig");
    }
}
