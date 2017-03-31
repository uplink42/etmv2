<?php

/* register/register_v.twig */
class __TwigTemplate_4c8c6a3d53b8f577b12c6f28f34e5014b4fc943ab10c21646ebde9011b9d62c1 extends Twig_Template
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
        echo "<script src=\"<?=base_url('assets/js/validate_register.js')?>\"></script>

<section class=\"content\">
    <div class=\"back-link\">
    <a href=\"<?=base_url()?>\" class=\"btn btn-accent\">Back to main page</a>
</div>

    <div class=\"container lg animated slideInDown\">
\t<div class=\"view-header\">
\t    <div class=\"header-icon\">
\t\t<i class=\"pe page-header-icon pe-7s-add-user\"></i>
\t    </div>
\t    <div class=\"header-title\">
\t\t<h3>Register in Eve Trade Master </h3>
\t\t<small>
\t\t    Make sure you set your API Key as \"no expiry\" to prevent possible errors.
\t\t</small>
\t    </div>
\t</div>

\t<div class=\"panel panel-filled\">
\t    <div class=\"panel-body\">
\t       <?php if(isset(\$result)) {?>
\t    <div class=\"text-center\">
\t\t<button class=\"btn btn-w-md btn-danger\">
\t\t    <?php foreach (\$result as \$error) {
\t\t\tif(!empty(\$error)) {
\t\t\t    echo \$error . \"<br>\";
\t\t\t}
\t\t    }?>
\t\t</button>
\t    </div>
\t\t<?php }
\t\t?>
\t\t<p></p>
\t\t<form action=\"<?=base_url('Register/processData')?>\" method=\"POST\" id=\"registerForm\" name=\"register\">
\t\t    <div class=\"row\">
\t\t\t<div class=\"form-group col-lg-6\">
\t\t\t    <label>Username</label>
\t\t\t    <input type=\"text\" id=\"username\" class=\"form-control\" name=\"username\" required='required' value=\"<?=set_value('username')?>\">
\t\t\t    <span class=\"help-block small\">Your unique username (6 characters minimum)</span>
\t\t\t</div>
\t\t\t<div class=\"form-group col-lg-6\">
\t\t\t    <label>Email Address</label>
\t\t\t    <input type=\"email\" id=\"email\" class=\"form-control\" name=\"email\" value=\"<?=set_value('email')?>\">
\t\t\t    <span class=\"help-block small\">Your valid email for password retrieval and automated reports</span>
\t\t\t</div>
\t\t\t<div class=\"form-group col-lg-6\">
\t\t\t    <label>Password</label>
\t\t\t    <input type=\"password\" id=\"password\" class=\"form-control\" name=\"password\" required='required' value=\"<?=set_value('password')?>\">
\t\t\t    <span class=\"help-block small\">Don't use the same password as Eve (6 characters minimum)</span>
\t\t\t</div>

\t\t\t<div class=\"form-group col-lg-6\">
\t\t\t    <label>Repeat Password</label>
\t\t\t    <input type=\"password\"  id=\"repeatpassword\" class=\"form-control\" name=\"repeatpassword\" required='required' value=\"<?=set_value('repeatpassword')?>\">
\t\t\t    <span class=\"help-block small\">Please repeat your password</span>
\t\t\t</div>
\t\t\t<div class=\"form-group col-lg-6\">
\t\t\t    <label>API Key</label>
\t\t\t    <input type=\"number\" id=\"apikey\" placeholder=\"Paste the KeyID generated below here\" class=\"form-control\" name=\"apikey\" required='required' value=\"<?=set_value('apikey')?>\">
\t\t\t    <strong><span class=\"help-block yellow\"><i class=\"fa fa-info\"></i> <a href=\"https://community.eveonline.com/support/api-key/CreatePredefined?accessMask=82317323\" target=\"_blank\">Generate key HERE. ETM only accepts keys created with this link!</a></span></strong>
\t\t\t</div>
\t\t\t<div class=\"form-group col-lg-6\">
\t\t\t    <label>vCode</label>
\t\t\t    <input type=\"text\" id=\"vcode\" class=\"form-control\" name=\"vcode\" required='required' value=\"<?=set_value('vcode')?>\">
\t\t\t    <span class=\"help-block small\">Paste the generated vCode here</span>
\t\t\t</div>
\t\t\t<div class=\"form-group col-lg-4 col-lg-offset-4\">
\t\t\t    <label>Automated reports</label>
\t\t\t    <select class=\"form-control\" id=\"reports\" name=\"reports\">
\t\t\t\t<option id=\"never\">none</option>
\t\t\t\t<option id=\"daily\">daily</option>
\t\t\t\t<option id=\"weekly\">weekly</option>
\t\t\t\t<option id=\"monthly\">monthly</option>                                 
\t\t\t    </select>
\t\t\t    <span class=\"help-block small\">Allow ETM to e-mail you detailed earnings reports. <br/>
\t\t\t\tThis can be changed anytime.</span>
\t\t\t</div>
\t\t    </div>
\t\t    <div class=\"text-center submit-register\">
\t\t\t<input type=\"Submit\" class=\"btn btn-accent\" name=\"register\" value=\"Register\">
\t\t    </div>
\t\t</form>
\t    </div>
\t</div>

    </div>
</section>
";
    }

    public function getTemplateName()
    {
        return "register/register_v.twig";
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
        return new Twig_Source("<script src=\"<?=base_url('assets/js/validate_register.js')?>\"></script>

<section class=\"content\">
    <div class=\"back-link\">
    <a href=\"<?=base_url()?>\" class=\"btn btn-accent\">Back to main page</a>
</div>

    <div class=\"container lg animated slideInDown\">
\t<div class=\"view-header\">
\t    <div class=\"header-icon\">
\t\t<i class=\"pe page-header-icon pe-7s-add-user\"></i>
\t    </div>
\t    <div class=\"header-title\">
\t\t<h3>Register in Eve Trade Master </h3>
\t\t<small>
\t\t    Make sure you set your API Key as \"no expiry\" to prevent possible errors.
\t\t</small>
\t    </div>
\t</div>

\t<div class=\"panel panel-filled\">
\t    <div class=\"panel-body\">
\t       <?php if(isset(\$result)) {?>
\t    <div class=\"text-center\">
\t\t<button class=\"btn btn-w-md btn-danger\">
\t\t    <?php foreach (\$result as \$error) {
\t\t\tif(!empty(\$error)) {
\t\t\t    echo \$error . \"<br>\";
\t\t\t}
\t\t    }?>
\t\t</button>
\t    </div>
\t\t<?php }
\t\t?>
\t\t<p></p>
\t\t<form action=\"<?=base_url('Register/processData')?>\" method=\"POST\" id=\"registerForm\" name=\"register\">
\t\t    <div class=\"row\">
\t\t\t<div class=\"form-group col-lg-6\">
\t\t\t    <label>Username</label>
\t\t\t    <input type=\"text\" id=\"username\" class=\"form-control\" name=\"username\" required='required' value=\"<?=set_value('username')?>\">
\t\t\t    <span class=\"help-block small\">Your unique username (6 characters minimum)</span>
\t\t\t</div>
\t\t\t<div class=\"form-group col-lg-6\">
\t\t\t    <label>Email Address</label>
\t\t\t    <input type=\"email\" id=\"email\" class=\"form-control\" name=\"email\" value=\"<?=set_value('email')?>\">
\t\t\t    <span class=\"help-block small\">Your valid email for password retrieval and automated reports</span>
\t\t\t</div>
\t\t\t<div class=\"form-group col-lg-6\">
\t\t\t    <label>Password</label>
\t\t\t    <input type=\"password\" id=\"password\" class=\"form-control\" name=\"password\" required='required' value=\"<?=set_value('password')?>\">
\t\t\t    <span class=\"help-block small\">Don't use the same password as Eve (6 characters minimum)</span>
\t\t\t</div>

\t\t\t<div class=\"form-group col-lg-6\">
\t\t\t    <label>Repeat Password</label>
\t\t\t    <input type=\"password\"  id=\"repeatpassword\" class=\"form-control\" name=\"repeatpassword\" required='required' value=\"<?=set_value('repeatpassword')?>\">
\t\t\t    <span class=\"help-block small\">Please repeat your password</span>
\t\t\t</div>
\t\t\t<div class=\"form-group col-lg-6\">
\t\t\t    <label>API Key</label>
\t\t\t    <input type=\"number\" id=\"apikey\" placeholder=\"Paste the KeyID generated below here\" class=\"form-control\" name=\"apikey\" required='required' value=\"<?=set_value('apikey')?>\">
\t\t\t    <strong><span class=\"help-block yellow\"><i class=\"fa fa-info\"></i> <a href=\"https://community.eveonline.com/support/api-key/CreatePredefined?accessMask=82317323\" target=\"_blank\">Generate key HERE. ETM only accepts keys created with this link!</a></span></strong>
\t\t\t</div>
\t\t\t<div class=\"form-group col-lg-6\">
\t\t\t    <label>vCode</label>
\t\t\t    <input type=\"text\" id=\"vcode\" class=\"form-control\" name=\"vcode\" required='required' value=\"<?=set_value('vcode')?>\">
\t\t\t    <span class=\"help-block small\">Paste the generated vCode here</span>
\t\t\t</div>
\t\t\t<div class=\"form-group col-lg-4 col-lg-offset-4\">
\t\t\t    <label>Automated reports</label>
\t\t\t    <select class=\"form-control\" id=\"reports\" name=\"reports\">
\t\t\t\t<option id=\"never\">none</option>
\t\t\t\t<option id=\"daily\">daily</option>
\t\t\t\t<option id=\"weekly\">weekly</option>
\t\t\t\t<option id=\"monthly\">monthly</option>                                 
\t\t\t    </select>
\t\t\t    <span class=\"help-block small\">Allow ETM to e-mail you detailed earnings reports. <br/>
\t\t\t\tThis can be changed anytime.</span>
\t\t\t</div>
\t\t    </div>
\t\t    <div class=\"text-center submit-register\">
\t\t\t<input type=\"Submit\" class=\"btn btn-accent\" name=\"register\" value=\"Register\">
\t\t    </div>
\t\t</form>
\t    </div>
\t</div>

    </div>
</section>
", "register/register_v.twig", "C:\\xampp\\htdocs\\v2\\application\\views\\register\\register_v.twig");
    }
}
