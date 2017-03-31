<?php

/* common/feedback_v.twig */
class __TwigTemplate_d406d25ab5983cefc62d6c42f0d319e28aebc230c943152b68d0b2fdda2dcdc3 extends Twig_Template
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
        echo "<div class=\"modal fade\" id=\"modal-feedback\" tabindex=\"-1\" role=\"dialog\" aria-hidden=\"true\" style=\"display: none;\">
    <div class=\"modal-dialog\">
        <div class=\"modal-content\">
            <div class=\"modal-header text-center\">
                <h4 class=\"modal-title\">Feedback & Bug Reports</h4>
            </div>
            <div class=\"modal-body\">
                <p>Here you can suggest new features or improvements for Eve Trade Master. I'm quite receptive to feedback and will usually provide an answer in a day at most. In case of bug reports, try to be specific. <a href=\"https://www.imgur.com\" target=\"_blank\">Consider sending screenshots to highlight any error messages or unwanted behaviour.</a> </p>
                <div class=\"form-group\">
                    <form class=\"submit-feedback\" method=\"POST\">
                        <input type=\"hidden\" name=\"email\" value=\"<?=\$email?>\">
                        <input type=\"hidden\" name=\"from_name\" value=\"<?=\$username?>\">
                        <input type=\"hidden\" name=\"to\" value=\"etmdevelopment42@gmail.com\">
                        <input type=\"hidden\" name=\"subject\" value=\"New Message from <?=\$username?>\">
                        <textarea class=\"form-control\" rows=\"4\" name=\"message\" autofocus></textarea>
                    </form>
                </div>
            </div>
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"btn btn-default btn-close\" data-dismiss=\"modal\">Close</button>
                <button type=\"button\" class=\"btn btn-accent btn-send-feedback\">Send</button>
            </div>
        </div>
    </div>
</div>";
    }

    public function getTemplateName()
    {
        return "common/feedback_v.twig";
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
        return new Twig_Source("<div class=\"modal fade\" id=\"modal-feedback\" tabindex=\"-1\" role=\"dialog\" aria-hidden=\"true\" style=\"display: none;\">
    <div class=\"modal-dialog\">
        <div class=\"modal-content\">
            <div class=\"modal-header text-center\">
                <h4 class=\"modal-title\">Feedback & Bug Reports</h4>
            </div>
            <div class=\"modal-body\">
                <p>Here you can suggest new features or improvements for Eve Trade Master. I'm quite receptive to feedback and will usually provide an answer in a day at most. In case of bug reports, try to be specific. <a href=\"https://www.imgur.com\" target=\"_blank\">Consider sending screenshots to highlight any error messages or unwanted behaviour.</a> </p>
                <div class=\"form-group\">
                    <form class=\"submit-feedback\" method=\"POST\">
                        <input type=\"hidden\" name=\"email\" value=\"<?=\$email?>\">
                        <input type=\"hidden\" name=\"from_name\" value=\"<?=\$username?>\">
                        <input type=\"hidden\" name=\"to\" value=\"etmdevelopment42@gmail.com\">
                        <input type=\"hidden\" name=\"subject\" value=\"New Message from <?=\$username?>\">
                        <textarea class=\"form-control\" rows=\"4\" name=\"message\" autofocus></textarea>
                    </form>
                </div>
            </div>
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"btn btn-default btn-close\" data-dismiss=\"modal\">Close</button>
                <button type=\"button\" class=\"btn btn-accent btn-send-feedback\">Send</button>
            </div>
        </div>
    </div>
</div>", "common/feedback_v.twig", "C:\\xampp\\htdocs\\v2\\application\\views\\common\\feedback_v.twig");
    }
}
