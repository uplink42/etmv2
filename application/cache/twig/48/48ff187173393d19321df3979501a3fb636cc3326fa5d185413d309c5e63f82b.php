<?php

/* main/citadeltax_v.twig */
class __TwigTemplate_42913aab7d5ddd82083207e9344c7db437cfdcbd9ef0213ea9a4d8b8e99c7a81 extends Twig_Template
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
    <div class=\"container-fluid\">
        <div class=\"row\">
            <div class=\"col-lg-12\">
                <div class=\"view-header\">
                    ";
        // line 6
        $this->loadTemplate(("common/selector_v" . ".twig"), "main/citadeltax_v.twig", 6)->display($context);
        // line 7
        echo "                    <div class=\"header-icon\">
                        ";
        // line 8
        if ((($context["aggregate"] ?? null) == 0)) {
            // line 9
            echo "                            <img alt=\"character portrait\" class=\"character-portrait\" src=\"https://image.eveonline.com/Character/";
            echo twig_escape_filter($this->env, ($context["character_id"] ?? null), "html", null, true);
            echo "_64.jpg\">
                        ";
        }
        // line 11
        echo "                        ";
        if ((($context["aggregate"] ?? null) != 0)) {
            // line 12
            echo "                            <i class=\"pe page-header-icon pe-7s-link\"></i>
                        ";
        }
        // line 14
        echo "                    </div>
                    <div class=\"header-title\">
                        <h1>
                            ";
        // line 17
        if ((($context["aggregate"] ?? null) == 1)) {
            // line 18
            echo "                                ";
            echo twig_escape_filter($this->env, twig_join_filter(($context["char_names"] ?? null), " + "), "html", null, true);
            echo " 's Citadel Taxes
                            ";
        }
        // line 20
        echo "                            ";
        if ((($context["aggregate"] ?? null) == 0)) {
            // line 21
            echo "                                's Citadel Taxes
                            ";
        }
        // line 23
        echo "                        </h1>
                    </div>
                </div>
                <hr>
            </div>
        </div>

        <div class=\"row\">
            <div class=\"col-md-12 col-xs-12\">
                <div class=\"panel panel-filled panel-c-success panel-main\">
                    <div class=\"panel-body\">
                        <i class=\"fa fa-info yellow\"></i> 
                            Here you can set or unset custom broker fees for transactions on certain Citadels of your choice
                        <br />
                        <i class=\"fa fa-info yellow\"></i> 
                            Setting a different tax for an existing entry will update it
                        <br />
                        ";
        // line 40
        if ((($context["aggregate"] ?? null) == 1)) {
            // line 41
            echo "                        <i class=\"fa fa-info yellow\"></i> 
                            You must be outside aggregated mode to use this page. Select any of your characters at the top right.
                        ";
        }
        // line 44
        echo "                    </div>
                </div> 
            </div>
        </div>
    
        ";
        // line 49
        if ((($context["aggregate"] ?? null) == 0)) {
            // line 50
            echo "        <div class=\"row\">
            <div class=\"col-md-6 col-xs-12\">
                <div class=\"panel panel-filled\">
                    <div class=\"panel-heading\">
                        <div class=\"panel panel-filled panel-c-success panel-collapse\">
                            <div class=\"panel-heading\">
                                <h5><i class=\"fa fa-usd\"></i> Assign a Custom Tax</h5>
                            </div>
                        </div>
                    </div>

                    <div class=\"panel-body tax-creation-panel\">
                        <form class=\"form-horizontal add-tax\" data-url=\"<?=base_url()?>\" method=\"POST\">
                            <div class=\"form-group\">
                                <label for=\"citadel\" class=\"col-sm-2 control-label\">Citadel</label>
                                <div class=\"col-sm-6\">
                                    <input type=\"text\" class=\"form-control origin-station\" id=\"citadel\" name=\"citadel\" placeholder=\"Begin typing and select one of the highlighted stations\" autofocus required>
                                </div>
                                <label for=\"tax\" class=\"col-sm-1 control-label\">Tax</label>
                                <div class=\"col-sm-2\">
                                    <input type=\"text\" class=\"form-control\" id=\"tax\" name=\"tax\" pattern=\"^(0(\\.\\d+)?|1(\\.0+)?)\$\" title=\"Must insert a decimal value (example: 0.01 for 1%\" required> 
                                </div>
                            </div>  
                            <input type=\"hidden\" value=\"<?=\$character_id?>\" name=\"character\" class=\"characterid\"> 
                            <div class=\"text-center\"><i class=\"fa fa-info\"></i> Broker fee must be inserted as a decimal (e.g 0.05 represents 5%)</div>
                            <button type=\"submit\" class=\"btn btn-default submit-tax\">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class=\"col-md-6 col-xs-12\">
                <div class=\"panel panel-filled\">
                    <div class=\"panel-heading\">
                        <div class=\"panel panel-filled panel-c-success panel-collapse\">
                            <div class=\"panel-heading\">
                                <h5><i class=\"fa fa-usd\"></i> Existing entries</h5>
                            </div>
                        </div>
                    </div>
                    <div class=\"panel-body tax-list\">
                        <div class=\"table-responsive\">
                            <table class=\"table table-responsive table-bordered table-hover table-stripped\">
                                <thead>
                                    <tr>
                                        <th>Citadel</th>
                                        <th style=\"width:20%\">Broker fee</th>
                                        <th style=\"width:20%\">Remove</th>
                                    </tr>
                                </thead>
                                <tbody class=\"tax-entries\">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        ";
        }
        // line 107
        echo "  
    </div>
</section>
<script src=\"";
        // line 110
        echo twig_escape_filter($this->env, base_url("dist/js/apps/citadeltax-app.js"), "html", null, true);
        echo "?HASH_CACHE=";
        echo twig_escape_filter($this->env, ($context["HASH_CACHE"] ?? null), "html", null, true);
        echo "\"></script>
";
    }

    public function getTemplateName()
    {
        return "main/citadeltax_v.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  166 => 110,  161 => 107,  101 => 50,  99 => 49,  92 => 44,  87 => 41,  85 => 40,  66 => 23,  62 => 21,  59 => 20,  53 => 18,  51 => 17,  46 => 14,  42 => 12,  39 => 11,  33 => 9,  31 => 8,  28 => 7,  26 => 6,  19 => 1,);
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
    <div class=\"container-fluid\">
        <div class=\"row\">
            <div class=\"col-lg-12\">
                <div class=\"view-header\">
                    {% include 'common/selector_v' ~ '.twig' %}
                    <div class=\"header-icon\">
                        {% if aggregate == 0 %}
                            <img alt=\"character portrait\" class=\"character-portrait\" src=\"https://image.eveonline.com/Character/{{character_id}}_64.jpg\">
                        {% endif %}
                        {% if aggregate != 0 %}
                            <i class=\"pe page-header-icon pe-7s-link\"></i>
                        {% endif %}
                    </div>
                    <div class=\"header-title\">
                        <h1>
                            {% if aggregate == 1 %}
                                {{ char_names | join(' + ') }} 's Citadel Taxes
                            {% endif %}
                            {% if aggregate == 0 %}
                                's Citadel Taxes
                            {% endif %}
                        </h1>
                    </div>
                </div>
                <hr>
            </div>
        </div>

        <div class=\"row\">
            <div class=\"col-md-12 col-xs-12\">
                <div class=\"panel panel-filled panel-c-success panel-main\">
                    <div class=\"panel-body\">
                        <i class=\"fa fa-info yellow\"></i> 
                            Here you can set or unset custom broker fees for transactions on certain Citadels of your choice
                        <br />
                        <i class=\"fa fa-info yellow\"></i> 
                            Setting a different tax for an existing entry will update it
                        <br />
                        {% if aggregate == 1 %}
                        <i class=\"fa fa-info yellow\"></i> 
                            You must be outside aggregated mode to use this page. Select any of your characters at the top right.
                        {% endif %}
                    </div>
                </div> 
            </div>
        </div>
    
        {% if aggregate == 0 %}
        <div class=\"row\">
            <div class=\"col-md-6 col-xs-12\">
                <div class=\"panel panel-filled\">
                    <div class=\"panel-heading\">
                        <div class=\"panel panel-filled panel-c-success panel-collapse\">
                            <div class=\"panel-heading\">
                                <h5><i class=\"fa fa-usd\"></i> Assign a Custom Tax</h5>
                            </div>
                        </div>
                    </div>

                    <div class=\"panel-body tax-creation-panel\">
                        <form class=\"form-horizontal add-tax\" data-url=\"<?=base_url()?>\" method=\"POST\">
                            <div class=\"form-group\">
                                <label for=\"citadel\" class=\"col-sm-2 control-label\">Citadel</label>
                                <div class=\"col-sm-6\">
                                    <input type=\"text\" class=\"form-control origin-station\" id=\"citadel\" name=\"citadel\" placeholder=\"Begin typing and select one of the highlighted stations\" autofocus required>
                                </div>
                                <label for=\"tax\" class=\"col-sm-1 control-label\">Tax</label>
                                <div class=\"col-sm-2\">
                                    <input type=\"text\" class=\"form-control\" id=\"tax\" name=\"tax\" pattern=\"^(0(\\.\\d+)?|1(\\.0+)?)\$\" title=\"Must insert a decimal value (example: 0.01 for 1%\" required> 
                                </div>
                            </div>  
                            <input type=\"hidden\" value=\"<?=\$character_id?>\" name=\"character\" class=\"characterid\"> 
                            <div class=\"text-center\"><i class=\"fa fa-info\"></i> Broker fee must be inserted as a decimal (e.g 0.05 represents 5%)</div>
                            <button type=\"submit\" class=\"btn btn-default submit-tax\">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class=\"col-md-6 col-xs-12\">
                <div class=\"panel panel-filled\">
                    <div class=\"panel-heading\">
                        <div class=\"panel panel-filled panel-c-success panel-collapse\">
                            <div class=\"panel-heading\">
                                <h5><i class=\"fa fa-usd\"></i> Existing entries</h5>
                            </div>
                        </div>
                    </div>
                    <div class=\"panel-body tax-list\">
                        <div class=\"table-responsive\">
                            <table class=\"table table-responsive table-bordered table-hover table-stripped\">
                                <thead>
                                    <tr>
                                        <th>Citadel</th>
                                        <th style=\"width:20%\">Broker fee</th>
                                        <th style=\"width:20%\">Remove</th>
                                    </tr>
                                </thead>
                                <tbody class=\"tax-entries\">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {% endif %}  
    </div>
</section>
<script src=\"{{ base_url('dist/js/apps/citadeltax-app.js') }}?HASH_CACHE={{HASH_CACHE}}\"></script>
", "main/citadeltax_v.twig", "C:\\xampp\\htdocs\\v2\\application\\views\\main\\citadeltax_v.twig");
    }
}
