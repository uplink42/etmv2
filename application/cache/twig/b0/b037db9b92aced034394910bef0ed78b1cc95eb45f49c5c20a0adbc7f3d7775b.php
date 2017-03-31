<?php

/* main/_template_v.twig */
class __TwigTemplate_dcb299811c4cbc243568aea7f51820d5e912993bb1c526126cd987a34532e1a8 extends Twig_Template
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
        echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset=\"utf-8\">
    <meta name=\"description\" content=\"eve trade master - web based profit tracker and asset manager for eve online\">
    <meta name=\"author\" content=\"nick starkey\">
    <meta name=viewport content=\"width=device-width, initial-scale=1\">
    <meta property=\"og:title\" content=\"Eve Trade Master\" />
    <meta name=\"keywords\" content=\"eve online trading market\">
    <meta name=\"google-site-verification\" content=\"AaRtrjK00fRsj5cWaYi3VnjiuOIpcRwZw4C860zpf9Y\" />
    <link href='//fonts.googleapis.com/css?family=Roboto:300,400,500,700,900' rel='stylesheet' type='text/css'>

    <!-- Page title -->
    <title>Eve Trade Master 2 - A web based Eve Online profit tracker, asset manager and trade analysis tool</title>

    ";
        // line 17
        echo "    ";
        if (twig_test_empty(($context["market"] ?? null))) {
            // line 18
            echo "        <script>window.paceOptions = { ajax: false }; </script>
    ";
        }
        // line 20
        echo "
    <!-- Vendor styles -->
    <link rel=\"stylesheet\" href=\"";
        // line 22
        echo twig_escape_filter($this->env, base_url("dist/luna/styles/styles.css"), "html", null, true);
        echo "?HASH_CACHE=";
        echo twig_escape_filter($this->env, ($context["HASH_CACHE"] ?? null), "html", null, true);
        echo "\"/>
    <link rel=\"stylesheet\" href=\"";
        // line 23
        echo twig_escape_filter($this->env, base_url("dist/luna/styles/theme.min.css"), "html", null, true);
        echo "?HASH_CACHE=";
        echo twig_escape_filter($this->env, ($context["HASH_CACHE"] ?? null), "html", null, true);
        echo "\"/>
    <script src=\"";
        // line 24
        echo twig_escape_filter($this->env, base_url("dist/js/apps.js"), "html", null, true);
        echo "?HASH_CACHE=";
        echo twig_escape_filter($this->env, ($context["HASH_CACHE"] ?? null), "html", null, true);
        echo "\"></script>

    ";
        // line 26
        if (($context["market"] ?? null)) {
            // line 27
            echo "        <script>
            \$(document).ready(function() {
                \$('body').addClass('pace-done');
            });
        </script>
    ";
        }
        // line 33
        echo "</head>
    ";
        // line 35
        echo "    ";
        if (($context["no_header"] ?? null)) {
            // line 36
            echo "        <body class='blank'>
    ";
        }
        // line 38
        echo "
    <div class=\"wrapper mainwrapper\" data-url=\"";
        // line 39
        echo twig_escape_filter($this->env, base_url(), "html", null, true);
        echo "\">

    ";
        // line 42
        echo "    ";
        if (($context["message"] ?? null)) {
            // line 43
            echo "        <script>toastr[";
            echo twig_escape_filter($this->env, ($context["notice"] ?? null), "html", null, true);
            echo "](";
            echo twig_escape_filter($this->env, ($context["message"] ?? null), "html", null, true);
            echo ")</script>
    ";
        }
        // line 45
        echo "
    ";
        // line 47
        echo "    ";
        if ($this->getAttribute(($context["SESSION"] ?? null), "msg", array(), "array")) {
            // line 48
            echo "        <script>toastr[";
            echo twig_escape_filter($this->env, $this->getAttribute(($context["SESSION"] ?? null), "notice", array(), "array"), "html", null, true);
            echo "](";
            echo twig_escape_filter($this->env, $this->getAttribute(($context["SESSION"] ?? null), "msg", array(), "array"), "html", null, true);
            echo ")</script>
    ";
        }
        // line 50
        echo "    
    ";
        // line 52
        echo "    ";
        if (twig_test_empty(($context["no_header"] ?? null))) {
            // line 53
            echo "        ";
            $this->loadTemplate("common/header_v.twig", "main/_template_v.twig", 53)->display($context);
            // line 54
            echo "    ";
        }
        // line 55
        echo "
    ";
        // line 57
        echo "    ";
        $this->loadTemplate((($context["view"] ?? null) . ".twig"), "main/_template_v.twig", 57)->display($context);
        // line 58
        echo "
    ";
        // line 59
        $this->loadTemplate(("common/footer_v" . ".twig"), "main/_template_v.twig", 59)->display($context);
        // line 60
        echo "
    </div>
    ";
        // line 62
        if (($context["email"] ?? null)) {
            // line 63
            echo "        ";
            $this->loadTemplate(("common/feedback_v" . ".twig"), "main/_template_v.twig", 63)->display($context);
            // line 64
            echo "    ";
        }
        // line 65
        echo "
        ";
        // line 67
        echo "        <div class=\"panel panel-filled panel-loading-common text-center\">
            <div class=\"panel-body\">
                Refreshing data... please wait
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

        ";
        // line 99
        echo "        <div class=\"panel-loading-ajax\">
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
        ";
        // line 126
        $this->loadTemplate(("analyticstracking" . ".php"), "main/_template_v.twig", 126)->display($context);
        // line 127
        echo "</body>";
    }

    public function getTemplateName()
    {
        return "main/_template_v.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  221 => 127,  219 => 126,  190 => 99,  157 => 67,  154 => 65,  151 => 64,  148 => 63,  146 => 62,  142 => 60,  140 => 59,  137 => 58,  134 => 57,  131 => 55,  128 => 54,  125 => 53,  122 => 52,  119 => 50,  111 => 48,  108 => 47,  105 => 45,  97 => 43,  94 => 42,  89 => 39,  86 => 38,  82 => 36,  79 => 35,  76 => 33,  68 => 27,  66 => 26,  59 => 24,  53 => 23,  47 => 22,  43 => 20,  39 => 18,  36 => 17,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset=\"utf-8\">
    <meta name=\"description\" content=\"eve trade master - web based profit tracker and asset manager for eve online\">
    <meta name=\"author\" content=\"nick starkey\">
    <meta name=viewport content=\"width=device-width, initial-scale=1\">
    <meta property=\"og:title\" content=\"Eve Trade Master\" />
    <meta name=\"keywords\" content=\"eve online trading market\">
    <meta name=\"google-site-verification\" content=\"AaRtrjK00fRsj5cWaYi3VnjiuOIpcRwZw4C860zpf9Y\" />
    <link href='//fonts.googleapis.com/css?family=Roboto:300,400,500,700,900' rel='stylesheet' type='text/css'>

    <!-- Page title -->
    <title>Eve Trade Master 2 - A web based Eve Online profit tracker, asset manager and trade analysis tool</title>

    {# disable pace on marketexplorer #}
    {% if market is empty %}
        <script>window.paceOptions = { ajax: false }; </script>
    {% endif %}

    <!-- Vendor styles -->
    <link rel=\"stylesheet\" href=\"{{ base_url('dist/luna/styles/styles.css') }}?HASH_CACHE={{HASH_CACHE}}\"/>
    <link rel=\"stylesheet\" href=\"{{ base_url('dist/luna/styles/theme.min.css') }}?HASH_CACHE={{HASH_CACHE}}\"/>
    <script src=\"{{ base_url('dist/js/apps.js') }}?HASH_CACHE={{HASH_CACHE}}\"></script>

    {% if market %}
        <script>
            \$(document).ready(function() {
                \$('body').addClass('pace-done');
            });
        </script>
    {% endif %}
</head>
    {# no header pages #}
    {% if no_header %}
        <body class='blank'>
    {% endif %}

    <div class=\"wrapper mainwrapper\" data-url=\"{{ base_url() }}\">

    {# toastr notification in same page #}
    {% if message %}
        <script>toastr[{{ notice }}]({{ message }})</script>
    {% endif %}

    {# toastr between pages #}
    {% if SESSION['msg'] %}
        <script>toastr[{{ SESSION['notice'] }}]({{ SESSION['msg'] }})</script>
    {% endif %}
    
    {# pages with header and sidenav #}
    {% if no_header is empty %}
        {% include 'common/header_v.twig' %}
    {% endif %}

    {# include current page #}
    {% include view ~ '.twig' %}

    {% include 'common/footer_v' ~ '.twig' %}

    </div>
    {% if email %}
        {% include 'common/feedback_v' ~ '.twig' %}
    {% endif %}

        {# loading... #}
        <div class=\"panel panel-filled panel-loading-common text-center\">
            <div class=\"panel-body\">
                Refreshing data... please wait
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

        {# loading inside app #}
        <div class=\"panel-loading-ajax\">
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
        {% include 'analyticstracking' ~ '.php' %}
</body>", "main/_template_v.twig", "C:\\xampp\\htdocs\\v2\\application\\views\\main\\_template_v.twig");
    }
}
