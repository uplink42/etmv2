<?php

/* common/selector_v.php */
class __TwigTemplate_e1c3663949896bf3b0abb29eb47a11cd315ffd98057140c94915307e087d9495 extends Twig_Template
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
    <?php  for(\$i=0; \$i<count(\$character_list['chars']); \$i++) { 
        echo \$i;
        \$selector['hasInterval'] ? \$intervalUri = \"/\".\$interval : \$intervalUri = \"\";
        \$selector['hasRegion'] ? \$regionUri = \"/\".\$region_id : \$regionUri = \"\";
        \$selector['gets'] ? \$get = \"&sig=\" . \$sig : \$get = \"\";
    ?>
        <li><a href=\"<?=base_url(\$selector['page'] . '/index/'.\$character_list['chars'][\$i] . \$intervalUri . \$regionUri . '?aggr=0' . \$get)?>\"><?=\$character_list['char_names'][\$i]?></a></li>    
    <?php } ?>
        <li role=\"separator\" class=\"divider\"></li>
        <?php \$url = \$selector['page'] . '/index/'.\$character_id . \$intervalUri . \$regionUri . '?aggr=1' . \$get;?>
        <li><a href=\"<?=base_url(\$url)?>\"><b>All</b></a></li>
    </ul>
</div>";
    }

    public function getTemplateName()
    {
        return "common/selector_v.php";
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
        return new Twig_Source("<div class=\"dropdown pull-right\">
    <button class=\"btn btn-default dropdown-toggle\" type=\"button\" id=\"dropdownmenu-characters\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"true\">
        Character
        <span class=\"caret\"></span>
    </button>
    <ul class=\"dropdown-menu dropdown-menu-right\">
    <?php  for(\$i=0; \$i<count(\$character_list['chars']); \$i++) { 
        echo \$i;
        \$selector['hasInterval'] ? \$intervalUri = \"/\".\$interval : \$intervalUri = \"\";
        \$selector['hasRegion'] ? \$regionUri = \"/\".\$region_id : \$regionUri = \"\";
        \$selector['gets'] ? \$get = \"&sig=\" . \$sig : \$get = \"\";
    ?>
        <li><a href=\"<?=base_url(\$selector['page'] . '/index/'.\$character_list['chars'][\$i] . \$intervalUri . \$regionUri . '?aggr=0' . \$get)?>\"><?=\$character_list['char_names'][\$i]?></a></li>    
    <?php } ?>
        <li role=\"separator\" class=\"divider\"></li>
        <?php \$url = \$selector['page'] . '/index/'.\$character_id . \$intervalUri . \$regionUri . '?aggr=1' . \$get;?>
        <li><a href=\"<?=base_url(\$url)?>\"><b>All</b></a></li>
    </ul>
</div>", "common/selector_v.php", "C:\\xampp\\htdocs\\v2\\application\\views\\common\\selector_v.php");
    }
}
