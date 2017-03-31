<?php

/* login/select_v.twig */
class __TwigTemplate_0a79859fd3af4be193203f1f4d5e4118e527019aa601f67c221476a775dac434 extends Twig_Template
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
    <a href=\"";
        // line 3
        echo twig_escape_filter($this->env, base_url(), "html", null, true);
        echo "\" class=\"btn btn-accent\">Back to main page</a>
</div>

    <div class=\"container lg animated slideInDown tabs-container\">
    \t<div class=\"view-header\">
    \t    <div class=\"header-icon\">
    \t\t  <i class=\"pe page-header-icon pe-7s-smile\"></i>
    \t    </div>
    \t    <div class=\"header-title\">
        \t\t<h3>Character selection</h3>
        \t\t<small>
        \t\t    Select one of the following character portraits to continue
        \t\t</small>
    \t    </div>
    \t</div>

        <script async src=\"//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js\"></script>
        <!-- ETM -->
        <ins class=\"adsbygoogle\"
             style=\"display:block\"
             data-ad-client=\"ca-pub-1952914247666990\"
             data-ad-slot=\"6026566466\"
             data-ad-format=\"auto\"></ins>
        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
        <br>

        <div class=\"panel panel-filled\">
            <div class=\"tabs-container\">
                <ul class=\"nav nav-tabs\">
                    <li class=\"active\"><a data-toggle=\"tab\" href=\"#tab-1\" aria-expanded=\"true\"> News</a></li>
                    <li class=\"\"><a data-toggle=\"tab\" href=\"#tab-2\" aria-expanded=\"false\">FAQ</a></li>
                    <li class=\"\"><a data-toggle=\"tab\" href=\"#tab-3\" aria-expanded=\"false\">Changelog</a></li>
                </ul>
            </div>
            <div class=\"tab-content\">
                <div id=\"tab-1\" class=\"tab-pane active\">
                    <div class=\"panel-body\">
                        <h5><strong class=\"c-white\">Latest News</strong></h5>
                        ";
        // line 41
        if (($context["cl_recent"] ?? null)) {
            // line 42
            echo "                            ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["cl_recent"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["row"]) {
                // line 43
                echo "                                <b> ";
                echo twig_escape_filter($this->env, $this->getAttribute($context["row"], "date", array()), "html", null, true);
                echo " </b> - ";
                echo $this->getAttribute($context["row"], "content", array());
                echo " <br>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['row'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 45
            echo "                        ";
        }
        // line 46
        echo "                    </div>
                </div>
                <div id=\"tab-2\" class=\"tab-pane\">
                    <div class=\"panel-body\">
                        <h5><strong class=\"c-white\">Frequently Asked Questions</strong></h5>
                        <p><b>Q: Is this service free?</b></p>
                                A: Yes, and there are no plans for charging players. If you would like to support this work or server costs and encourage further development feel free to Donate (ISK or \$) trough the link in the footer. Disabling your ad-block is also a great help!
                        <br><br>
                        <p><b>Q: Your website isn't pulling my data!</b></p>
                        A: As long as you're able to login and select a character it should be. Remember that Eve Online's API only updates data after certain intervals. These are:
                        <ul>
                            <li>Account Balance: every 15 minutes</li>
                            <li>API key data: every 5 minutes</li>
                            <li>Asset list: every 2 hours</li>
                            <li>Contract list: every 60 minutes</li>
                            <li>Character skills: every 60 minutes</li>
                            <li>Market Orders: every 60 minutes</li>
                            <li>Character standings: every 3 hours</li>
                            <li>Wallet transactions: every 30 minutes</li>
                            <li>CREST Market data: every 5 minutes</li>
                        </ul>
                        If you're absolutely sure your data isn't being pulled drop me a mail at etmdevelopment42 at gmail.com
                        <br><br>
                        <p><b>Q: How do you calculate profits?</b></p>
                        A: Profits are calculated using a <a href='http://www.accountingtools.com/fifo-method' target='blank'>First-in-First-Out </a>method. This means the first items you buy are assumed to be the first items you sell.
                        It's currently impossible to keep a track of each individual item with the current Eve API so this might lead to some inconsistencies in results if you buy items for other purposes on your trading characters.
                        If you want clean results I recommend you to not purchase items you don't intend to re-sell with the characters you listed in ETM.
                        <br><br>
                        <p><b>Q: Can I have the source code?</b></p>
                        A: The source is available on <a href=\"https://github.com/uplink42/etmv2/tree/dev\" target='blank'>github</a>
                        <br><br>
                        <p><b>Q: Will there be new features after the Beta is over?</b></p>
                        A: Yes. Things like corp API key support are definetly in the list. However I don't have as much time to devote to side projects and can't guarantee any timelines.
                    </div>
                </div>
                <div id=\"tab-3\" class=\"tab-pane\">
                    <div class=\"panel-body\">
                        <h5><strong class=\"c-white\">Changelog</strong></h5>
                        ";
        // line 84
        if (($context["cl"] ?? null)) {
            // line 85
            echo "                            ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["cl"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["row"]) {
                // line 86
                echo "                                <b> ";
                echo twig_escape_filter($this->env, $this->getAttribute($context["row"], "date", array()), "html", null, true);
                echo " </b> - ";
                echo $this->getAttribute($context["row"], "content", array());
                echo " <br>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['row'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 88
            echo "                        ";
        }
        // line 89
        echo "                    </div>
                </div>
            </div>
        </div>

    \t<div class=\"panel panel-filled\">
    \t    <div class=\"panel-body\">
    \t\t<p></p>
        \t\t<div class=\"table-responsive\">
                    ";
        // line 98
        if (($context["table"] ?? null)) {
            // line 99
            echo "            \t\t    <table class=\"table table-responsive table-bordered table-stripped table-hover\">
                \t\t\t<thead>
                \t\t\t    <tr>
                \t\t\t\t    <th></th>
                \t\t\t\t    <th>Name</th>
                \t\t\t\t    <th>Wallet Balance</th>
                \t\t\t\t    <th>Assets Value</th>
                \t\t\t\t    <th>Escrow</th>
                \t\t\t\t    <th>Sell Orders</th>
                \t\t\t    </tr>
                \t\t\t</thead>
                \t\t\t<tbody>
                            ";
            // line 111
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute(($context["table"] ?? null), 0, array(), "array"), "character", array(), "array"));
            foreach ($context['_seq'] as $context["_key"] => $context["char"]) {
                // line 112
                echo "
                \t\t\t\t";
                // line 119
                echo "                \t\t\t\t<tr>
                \t\t\t\t    <td><a href='";
                // line 120
                echo twig_escape_filter($this->env, base_url(("dashboard/index/" . $this->getAttribute($context["char"], "id", array(), "array"))), "html", null, true);
                echo "'>
                                        <img src='https://image.eveonline.com/Character/";
                // line 121
                echo twig_escape_filter($this->env, $this->getAttribute($context["char"], "id", array(), "array"), "html", null, true);
                echo "_32.jpg' alt='character portrait'></a>
                                    </td>
                \t\t\t\t    <td>";
                // line 123
                echo twig_escape_filter($this->env, $this->getAttribute($context["char"], "name", array(), "array"), "html", null, true);
                echo "</td>
                \t\t\t\t    <td class=\"text-right\">";
                // line 124
                echo twig_escape_filter($this->env, twig_number_format_filter($this->env, $this->getAttribute($context["char"], "balance", array(), "array")), "html", null, true);
                echo "</td>
                \t\t\t\t    <td class=\"text-right\">";
                // line 125
                echo twig_escape_filter($this->env, twig_number_format_filter($this->env, $this->getAttribute($context["char"], "networth", array(), "array")), "html", null, true);
                echo "</td>
                \t\t\t\t    <td class=\"text-right\">";
                // line 126
                echo twig_escape_filter($this->env, twig_number_format_filter($this->env, $this->getAttribute($context["char"], "escrow", array(), "array")), "html", null, true);
                echo "</td>
                \t\t\t\t    <td class=\"text-right\">";
                // line 127
                echo twig_escape_filter($this->env, twig_number_format_filter($this->env, $this->getAttribute($context["char"], "sell", array(), "array")), "html", null, true);
                echo "</td>
                \t\t\t\t</tr>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['char'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 130
            echo "                \t\t\t    
                \t\t\t    ";
            // line 135
            echo "                \t\t\t\t<tr>
                \t\t\t\t    <td></td>
                \t\t\t\t    <td><strong>Total</strong></td>
                \t\t\t\t    <td class=\"text-right\"><strong>";
            // line 138
            echo twig_escape_filter($this->env, twig_number_format_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute(($context["table"] ?? null), 0, array(), "array"), "total", array(), "array"), "balance_total", array(), "array")), "html", null, true);
            echo "</strong></td>
                \t\t\t\t    <td class=\"text-right\"><strong>";
            // line 139
            echo twig_escape_filter($this->env, twig_number_format_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute(($context["table"] ?? null), 0, array(), "array"), "total", array(), "array"), "networth_total", array(), "array")), "html", null, true);
            echo "</strong></td>
                \t\t\t\t    <td class=\"text-right\"><strong>";
            // line 140
            echo twig_escape_filter($this->env, twig_number_format_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute(($context["table"] ?? null), 0, array(), "array"), "total", array(), "array"), "escrow_total", array(), "array")), "html", null, true);
            echo "</strong></td>
                \t\t\t\t    <td class=\"text-right\"><strong>";
            // line 141
            echo twig_escape_filter($this->env, twig_number_format_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute(($context["table"] ?? null), 0, array(), "array"), "total", array(), "array"), "sell_total", array(), "array")), "html", null, true);
            echo "</strong></td>
                \t\t\t\t</tr>
                \t\t\t\t<tr>
                \t\t\t\t    <td></td>
                \t\t\t\t    <td></td>
                \t\t\t\t    <td></td>
                \t\t\t\t    <td></td>
                \t\t\t\t    <td class=\"text-right yellow\"><strong>GRAND TOTAL</strong></td>
                \t\t\t\t    <td class=\"text-right yellow\"><strong>";
            // line 149
            echo twig_escape_filter($this->env, twig_number_format_filter($this->env, $this->getAttribute($this->getAttribute(($context["table"] ?? null), 0, array(), "array"), "grand_total", array(), "array")), "html", null, true);
            echo "</strong></td>
                \t\t\t\t</tr>
                \t\t\t</tbody>
                        </table>
                    ";
        }
        // line 154
        echo "        \t\t</div>        
            </div>
        </div>\t
    </div>
</section>

";
    }

    public function getTemplateName()
    {
        return "login/select_v.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  256 => 154,  248 => 149,  237 => 141,  233 => 140,  229 => 139,  225 => 138,  220 => 135,  217 => 130,  208 => 127,  204 => 126,  200 => 125,  196 => 124,  192 => 123,  187 => 121,  183 => 120,  180 => 119,  177 => 112,  173 => 111,  159 => 99,  157 => 98,  146 => 89,  143 => 88,  132 => 86,  127 => 85,  125 => 84,  85 => 46,  82 => 45,  71 => 43,  66 => 42,  64 => 41,  23 => 3,  19 => 1,);
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
    <a href=\"{{ base_url() }}\" class=\"btn btn-accent\">Back to main page</a>
</div>

    <div class=\"container lg animated slideInDown tabs-container\">
    \t<div class=\"view-header\">
    \t    <div class=\"header-icon\">
    \t\t  <i class=\"pe page-header-icon pe-7s-smile\"></i>
    \t    </div>
    \t    <div class=\"header-title\">
        \t\t<h3>Character selection</h3>
        \t\t<small>
        \t\t    Select one of the following character portraits to continue
        \t\t</small>
    \t    </div>
    \t</div>

        <script async src=\"//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js\"></script>
        <!-- ETM -->
        <ins class=\"adsbygoogle\"
             style=\"display:block\"
             data-ad-client=\"ca-pub-1952914247666990\"
             data-ad-slot=\"6026566466\"
             data-ad-format=\"auto\"></ins>
        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
        <br>

        <div class=\"panel panel-filled\">
            <div class=\"tabs-container\">
                <ul class=\"nav nav-tabs\">
                    <li class=\"active\"><a data-toggle=\"tab\" href=\"#tab-1\" aria-expanded=\"true\"> News</a></li>
                    <li class=\"\"><a data-toggle=\"tab\" href=\"#tab-2\" aria-expanded=\"false\">FAQ</a></li>
                    <li class=\"\"><a data-toggle=\"tab\" href=\"#tab-3\" aria-expanded=\"false\">Changelog</a></li>
                </ul>
            </div>
            <div class=\"tab-content\">
                <div id=\"tab-1\" class=\"tab-pane active\">
                    <div class=\"panel-body\">
                        <h5><strong class=\"c-white\">Latest News</strong></h5>
                        {% if cl_recent %}
                            {% for row in cl_recent %}
                                <b> {{ row.date }} </b> - {{ row.content | raw }} <br>
                            {% endfor %}
                        {% endif %}
                    </div>
                </div>
                <div id=\"tab-2\" class=\"tab-pane\">
                    <div class=\"panel-body\">
                        <h5><strong class=\"c-white\">Frequently Asked Questions</strong></h5>
                        <p><b>Q: Is this service free?</b></p>
                                A: Yes, and there are no plans for charging players. If you would like to support this work or server costs and encourage further development feel free to Donate (ISK or \$) trough the link in the footer. Disabling your ad-block is also a great help!
                        <br><br>
                        <p><b>Q: Your website isn't pulling my data!</b></p>
                        A: As long as you're able to login and select a character it should be. Remember that Eve Online's API only updates data after certain intervals. These are:
                        <ul>
                            <li>Account Balance: every 15 minutes</li>
                            <li>API key data: every 5 minutes</li>
                            <li>Asset list: every 2 hours</li>
                            <li>Contract list: every 60 minutes</li>
                            <li>Character skills: every 60 minutes</li>
                            <li>Market Orders: every 60 minutes</li>
                            <li>Character standings: every 3 hours</li>
                            <li>Wallet transactions: every 30 minutes</li>
                            <li>CREST Market data: every 5 minutes</li>
                        </ul>
                        If you're absolutely sure your data isn't being pulled drop me a mail at etmdevelopment42 at gmail.com
                        <br><br>
                        <p><b>Q: How do you calculate profits?</b></p>
                        A: Profits are calculated using a <a href='http://www.accountingtools.com/fifo-method' target='blank'>First-in-First-Out </a>method. This means the first items you buy are assumed to be the first items you sell.
                        It's currently impossible to keep a track of each individual item with the current Eve API so this might lead to some inconsistencies in results if you buy items for other purposes on your trading characters.
                        If you want clean results I recommend you to not purchase items you don't intend to re-sell with the characters you listed in ETM.
                        <br><br>
                        <p><b>Q: Can I have the source code?</b></p>
                        A: The source is available on <a href=\"https://github.com/uplink42/etmv2/tree/dev\" target='blank'>github</a>
                        <br><br>
                        <p><b>Q: Will there be new features after the Beta is over?</b></p>
                        A: Yes. Things like corp API key support are definetly in the list. However I don't have as much time to devote to side projects and can't guarantee any timelines.
                    </div>
                </div>
                <div id=\"tab-3\" class=\"tab-pane\">
                    <div class=\"panel-body\">
                        <h5><strong class=\"c-white\">Changelog</strong></h5>
                        {% if cl %}
                            {% for row in cl %}
                                <b> {{ row.date }} </b> - {{ row.content | raw }} <br>
                            {% endfor %}
                        {% endif %}
                    </div>
                </div>
            </div>
        </div>

    \t<div class=\"panel panel-filled\">
    \t    <div class=\"panel-body\">
    \t\t<p></p>
        \t\t<div class=\"table-responsive\">
                    {% if table %}
            \t\t    <table class=\"table table-responsive table-bordered table-stripped table-hover\">
                \t\t\t<thead>
                \t\t\t    <tr>
                \t\t\t\t    <th></th>
                \t\t\t\t    <th>Name</th>
                \t\t\t\t    <th>Wallet Balance</th>
                \t\t\t\t    <th>Assets Value</th>
                \t\t\t\t    <th>Escrow</th>
                \t\t\t\t    <th>Sell Orders</th>
                \t\t\t    </tr>
                \t\t\t</thead>
                \t\t\t<tbody>
                            {% for char in table[0]['character'] %}

                \t\t\t\t{# \$id = \$char['id'];
                \t\t\t\t\$name = \$char['name'];
                \t\t\t\t\$balance = \$char['balance'];
                \t\t\t\t\$networth = \$char['networth'];
                \t\t\t\t\$escrow = \$char['escrow'];
                \t\t\t\t\$sell = \$char['sell']; #}
                \t\t\t\t<tr>
                \t\t\t\t    <td><a href='{{ base_url('dashboard/index/' ~ char['id']) }}'>
                                        <img src='https://image.eveonline.com/Character/{{ char['id'] }}_32.jpg' alt='character portrait'></a>
                                    </td>
                \t\t\t\t    <td>{{ char['name'] }}</td>
                \t\t\t\t    <td class=\"text-right\">{{ char['balance']  | number_format }}</td>
                \t\t\t\t    <td class=\"text-right\">{{ char['networth'] | number_format }}</td>
                \t\t\t\t    <td class=\"text-right\">{{ char['escrow']   | number_format }}</td>
                \t\t\t\t    <td class=\"text-right\">{{ char['sell']     | number_format }}</td>
                \t\t\t\t</tr>
                            {% endfor %}
                \t\t\t    
                \t\t\t    {# \$balance_total = \$table[0]['total']['balance_total'];
                \t\t\t    \$networth_total = \$table[0]['total']['networth_total'];
                \t\t\t    \$escrow_total = \$table[0]['total']['escrow_total'];
                \t\t\t    \$sell_total = \$table[0]['total']['sell_total']; #}
                \t\t\t\t<tr>
                \t\t\t\t    <td></td>
                \t\t\t\t    <td><strong>Total</strong></td>
                \t\t\t\t    <td class=\"text-right\"><strong>{{ table[0]['total']['balance_total']  | number_format }}</strong></td>
                \t\t\t\t    <td class=\"text-right\"><strong>{{ table[0]['total']['networth_total'] | number_format }}</strong></td>
                \t\t\t\t    <td class=\"text-right\"><strong>{{ table[0]['total']['escrow_total']   | number_format }}</strong></td>
                \t\t\t\t    <td class=\"text-right\"><strong>{{ table[0]['total']['sell_total']     | number_format }}</strong></td>
                \t\t\t\t</tr>
                \t\t\t\t<tr>
                \t\t\t\t    <td></td>
                \t\t\t\t    <td></td>
                \t\t\t\t    <td></td>
                \t\t\t\t    <td></td>
                \t\t\t\t    <td class=\"text-right yellow\"><strong>GRAND TOTAL</strong></td>
                \t\t\t\t    <td class=\"text-right yellow\"><strong>{{ table[0]['grand_total'] | number_format }}</strong></td>
                \t\t\t\t</tr>
                \t\t\t</tbody>
                        </table>
                    {% endif %}
        \t\t</div>        
            </div>
        </div>\t
    </div>
</section>

", "login/select_v.twig", "C:\\xampp\\htdocs\\v2\\application\\views\\login\\select_v.twig");
    }
}
