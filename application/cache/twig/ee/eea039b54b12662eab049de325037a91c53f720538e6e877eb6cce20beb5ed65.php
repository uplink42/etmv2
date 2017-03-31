<?php

/* common/selector_v.twig */
class __TwigTemplate_3ba42a19e9e67eee939599c85b9db20e16fa5aed9ff3655d11c3c679dcf3eca4 extends Twig_Template
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
        echo "<div class=\"dropdown pull-right\">
    <button class=\"btn btn-default dropdown-toggle\" type=\"button\" id=\"dropdownmenu-characters\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"true\">
        Character
        <span class=\"caret\"></span>
    </button>
    <ul class=\"dropdown-menu dropdown-menu-right\">
        ";
        // line 7
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["character_list"] ?? null), "chars", array(), "array"));
        $context['loop'] = array(
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        );
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["char"]) {
            // line 8
            echo "            <li><a href=\"";
            echo twig_escape_filter($this->env, base_url($this->getAttribute(($context["selector"] ?? null), "page", array(), "array")), "html", null, true);
            echo "/index/";
            echo twig_escape_filter($this->env, $context["char"], "html", null, true);
            echo twig_escape_filter($this->env, (($this->getAttribute(($context["selector"] ?? null), "hasInterval", array(), "array")) ? (("/" . ($context["interval"] ?? null))) : ("")), "html", null, true);
            echo twig_escape_filter($this->env, (($this->getAttribute(($context["selector"] ?? null), "hasRegion", array(), "array")) ? (("/" . ($context["region_id"] ?? null))) : ("")), "html", null, true);
            echo "?aggr=0";
            echo twig_escape_filter($this->env, ($context["get"] ?? null), "html", null, true);
            echo "\"> ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["character_list"] ?? null), "char_names", array(), "array"), $this->getAttribute($context["loop"], "index0", array()), array(), "array"), "html", null, true);
            echo " </a>
            </li>
        ";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['char'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 11
        echo "        <li role=\"separator\" class=\"divider\"></li>
        <li><a href=\"";
        // line 12
        echo twig_escape_filter($this->env, base_url($this->getAttribute(($context["selector"] ?? null), "page", array(), "array")), "html", null, true);
        echo "/index/";
        echo twig_escape_filter($this->env, ($context["character_id"] ?? null), "html", null, true);
        echo twig_escape_filter($this->env, (($this->getAttribute(($context["selector"] ?? null), "hasInterval", array(), "array")) ? (("/" . ($context["interval"] ?? null))) : ("")), "html", null, true);
        echo twig_escape_filter($this->env, (($this->getAttribute(($context["selector"] ?? null), "hasRegion", array(), "array")) ? (("/" . ($context["region_id"] ?? null))) : ("")), "html", null, true);
        echo "?aggr=1";
        echo twig_escape_filter($this->env, ($context["get"] ?? null), "html", null, true);
        echo "\"> <b>All</b> </a>
        </li>
    </ul>
</div>";
    }

    public function getTemplateName()
    {
        return "common/selector_v.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  73 => 12,  70 => 11,  44 => 8,  27 => 7,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("<div class=\"dropdown pull-right\">
    <button class=\"btn btn-default dropdown-toggle\" type=\"button\" id=\"dropdownmenu-characters\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"true\">
        Character
        <span class=\"caret\"></span>
    </button>
    <ul class=\"dropdown-menu dropdown-menu-right\">
        {% for char in character_list['chars'] %}
            <li><a href=\"{{ base_url(selector['page']) }}/index/{{ char }}{{ selector['hasInterval'] ? '/' ~ interval : '' }}{{ selector['hasRegion'] ? \"/\" ~ region_id : '' }}?aggr=0{{ get }}\"> {{ character_list['char_names'][loop.index0] }} </a>
            </li>
        {% endfor %}
        <li role=\"separator\" class=\"divider\"></li>
        <li><a href=\"{{ base_url(selector['page']) }}/index/{{ character_id }}{{ selector['hasInterval'] ? '/' ~ interval : '' }}{{ selector['hasRegion'] ? \"/\" ~ region_id : '' }}?aggr=1{{ get }}\"> <b>All</b> </a>
        </li>
    </ul>
</div>", "common/selector_v.twig", "C:\\xampp\\htdocs\\v2\\application\\views\\common\\selector_v.twig");
    }
}
