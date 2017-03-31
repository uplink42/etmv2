<?php

/* common/footer_v.twig */
class __TwigTemplate_eb909064a5b6761e8a4d0de06eb9b6e025940da8e6e3321f1b137f8630f3a1be extends Twig_Template
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
        if (twig_test_empty(($context["no_header"] ?? null))) {
            // line 2
            echo "    <div class=\"col-lg-4 col-lg-offset-4 col-sm-5 col-sm-offset-5 col-xs-12 col-xs-offset-0\">
";
        }
        // line 4
        if (($context["no_header"] ?? null)) {
            // line 5
            echo "    <div class=\"col-lg-6 col-lg-offset-3 col-lg-6 col-lg-offset-3 col-sm-8 col-sm-offset-2 col-xs-12 col-xs-offset-0\">
";
        }
        // line 7
        echo "
    <div class=\"panel panel-filled panel-c-warning footer-panel\">
        <div class=\"panel-heading text-center footer-links\">
            <div class=\"panel-tools\">
                <a class=\"panel-toggle\"><i class=\"fa fa-chevron-up\"></i></a>
                <a class=\"panel-close\"><i class=\"fa fa-times\"></i></a>
            </div>
            <ul class=\"list-inline\">
                <li><a href=\"";
        // line 15
        echo twig_escape_filter($this->env, base_url(), "html", null, true);
        echo "\">Home</a></li>
                ";
        // line 16
        if (($context["email"] ?? null)) {
            // line 17
            echo "                    <li><a data-toggle=\"modal\" data-target=\"#modal-feedback\">Feedback and bug reports</a></li>
                ";
        }
        // line 19
        echo "                <li><a href=\"https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=E92PVNRT3L9EQ\" target=\"_blank\">Donate</a></li>
                <li><a href=\"https://www.evetrademaster.com/blog\" target=\"_blank\">Blog</a></li>
            </ul>
        </div>
        <div class=\"panel-body text-center footer-desc\" style=\"display: block;\">
            © Eve Trade Master 2017 - design and development by uplink42<br>
            Eve Online, the Eve logo and all associated logos and designs are intellectual property of CCP hf,
            and are under copyright. That means copying them is not right. <br>
        </div>
    </div>
    <script async src=\"//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js\"></script>
\t\t<!-- ETM -->
\t\t<ins class=\"adsbygoogle\"
\t\t     style=\"display:block\"
\t\t     data-ad-client=\"ca-pub-1952914247666990\"
\t\t     data-ad-slot=\"6026566466\"
\t\t     data-ad-format=\"auto\"></ins>
\t\t<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
</div>

";
    }

    public function getTemplateName()
    {
        return "common/footer_v.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  51 => 19,  47 => 17,  45 => 16,  41 => 15,  31 => 7,  27 => 5,  25 => 4,  21 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% if no_header is empty %}
    <div class=\"col-lg-4 col-lg-offset-4 col-sm-5 col-sm-offset-5 col-xs-12 col-xs-offset-0\">
{% endif %}
{% if no_header %}
    <div class=\"col-lg-6 col-lg-offset-3 col-lg-6 col-lg-offset-3 col-sm-8 col-sm-offset-2 col-xs-12 col-xs-offset-0\">
{% endif %}

    <div class=\"panel panel-filled panel-c-warning footer-panel\">
        <div class=\"panel-heading text-center footer-links\">
            <div class=\"panel-tools\">
                <a class=\"panel-toggle\"><i class=\"fa fa-chevron-up\"></i></a>
                <a class=\"panel-close\"><i class=\"fa fa-times\"></i></a>
            </div>
            <ul class=\"list-inline\">
                <li><a href=\"{{ base_url() }}\">Home</a></li>
                {% if email %}
                    <li><a data-toggle=\"modal\" data-target=\"#modal-feedback\">Feedback and bug reports</a></li>
                {% endif %}
                <li><a href=\"https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=E92PVNRT3L9EQ\" target=\"_blank\">Donate</a></li>
                <li><a href=\"https://www.evetrademaster.com/blog\" target=\"_blank\">Blog</a></li>
            </ul>
        </div>
        <div class=\"panel-body text-center footer-desc\" style=\"display: block;\">
            © Eve Trade Master 2017 - design and development by uplink42<br>
            Eve Online, the Eve logo and all associated logos and designs are intellectual property of CCP hf,
            and are under copyright. That means copying them is not right. <br>
        </div>
    </div>
    <script async src=\"//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js\"></script>
\t\t<!-- ETM -->
\t\t<ins class=\"adsbygoogle\"
\t\t     style=\"display:block\"
\t\t     data-ad-client=\"ca-pub-1952914247666990\"
\t\t     data-ad-slot=\"6026566466\"
\t\t     data-ad-format=\"auto\"></ins>
\t\t<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
</div>

", "common/footer_v.twig", "C:\\xampp\\htdocs\\v2\\application\\views\\common\\footer_v.twig");
    }
}
