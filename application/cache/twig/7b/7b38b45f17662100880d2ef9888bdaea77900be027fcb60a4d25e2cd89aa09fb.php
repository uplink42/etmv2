<?php

/* common/header_v.php */
class __TwigTemplate_bc26c56cc97bf5f1b936b3962f256aac23577c9b31dbe86ce121a53f0ac897c7 extends Twig_Template
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
        echo "<!-- Header-->
<nav class=\"navbar navbar-default navbar-fixed-top\" data-id=\"<?=\$character_id?>\" data-url=\"<?=base_url()?>\">
    <div class=\"container-fluid\">
        <div class=\"navbar-header\">
            <div id=\"mobile-menu\">
                <div class=\"left-nav-toggle\">
                    <a href=\"#\">
                        <i class=\"stroke-hamburgermenu\">
                        </i>
                    </a>
                </div>
            </div>
            <a class=\"navbar-brand nav-u\" data-selected=\"<?=\$selected?>\" href=\"<?=base_url('Updater')?>\">
                ETM
                <span>
                    v.2.0
                </span>
                <i class=\"fa fa-refresh\">
                </i>
            </a>
        </div>
        <div class=\"navbar-collapse collapse\" id=\"navbar\">
            <div class=\"left-nav-toggle\">
                <a href=\"#\">
                    <i class=\"stroke-hamburgermenu\">
                    </i>
                </a>
            </div>
            <ul class=\"nav navbar-nav navbar-right\">
                <li class=\"profil-link\" data-aggr=\"<?=\$aggregate?>\" data-character=\"<?=\$character_id?>\" data-url=\"<?=base_url()?>\">
                    <a href=\"<?=base_url('Updater')?>\">
                        <span class=\"profile-address\">
                            <i class=\"fa fa-balance-scale\">
                            </i>
                            <span class=\"header-balance\" title=\"balance\">
                            </span>
                            |
                            <i class=\"fa fa-briefcase\">
                            </i>
                            <span class=\"header-networth\" title=\"assets\">
                            </span>
                            |
                            <i class=\"pe-7s-cart\">
                            </i>
                            <span class=\"header-orders\" title=\"sell orders\">
                            </span>
                            |
                            <i class=\"pe-7s-culture\">
                            </i>
                            <span class=\"header-escrow\" title=\"escrow\">
                            </span>
                        </span>
                        <i class=\"fa fa-refresh\"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- End header-->
<!-- Navigation-->
<aside class=\"navigation\">
    <nav>
        <ul class=\"nav luna-nav\">
            <li class=\"nav-category\">
                Profit Tracking
            </li>
            <li class=\"dashboard\">
                <a href=\"<?=base_url('dashboard/index/'.\$character_id .'?aggr='.\$aggregate)?>\">
                     <i class=\"fa fa-tachometer\"></i> Dashboard
                </a>
            </li>
            <li class=\"profits\">
                <a href=\"<?=base_url('profits/index/'.\$character_id .'?aggr='.\$aggregate)?>\">
                    <i class=\"fa fa-line-chart\"></i> Profit Breakdown
                </a>
            </li>
            <li class=\"statistics\">
                <a href=\"<?=base_url('statistics/index/'.\$character_id .'?aggr='.\$aggregate)?>\">
                    <i class=\"fa fa-area-chart\"></i> Statistics
                </a>
            </li>
            <li class=\"nav-category\">
                Archive
            </li>
            <li class=\"transactions\">
                <a href=\"<?=base_url('transactions/index/'.\$character_id .'?aggr='.\$aggregate)?>\">
                    <i class=\"fa fa-list-ol\"></i> Transactions
                </a>
            </li>
            <li class=\"marketorders\">
                <a href=\"<?=base_url('marketorders/index/'.\$character_id .'?aggr='.\$aggregate)?>\">
                    <i class=\"fa fa-shopping-cart\"></i> Market Orders
                </a>
            </li>
            <li class=\"contracts\">
                <a href=\"<?=base_url('contracts/index/'.\$character_id .'?aggr='.\$aggregate)?>\">
                    <i class=\"fa fa-files-o\"></i> Contracts
                </a>
            </li>
            <li class=\"nav-category\">
                Net Worth Evaluation
            </li>
            <li class=\"assets\">
                <a href=\"<?=base_url('assets/index/'.\$character_id .'?aggr='.\$aggregate)?>\">
                    <i class=\"fa fa-suitcase\"></i> Assets
                </a>
            </li>
            <li class=\"networth\">
                <a href=\"<?=base_url('networthtracker/index/'.\$character_id .'?aggr='.\$aggregate)?>\">
                    <i class=\"fa fa-bar-chart\"></i> Net worth Tracker
                </a>
            </li>
            <li class=\"nav-category\">
                <i class=\"fa fa-magic\"></i> Trade Assistant
            </li>
            <li class=\"marketexplorer\">
                <a href=\"<?=base_url('marketexplorer/index/'.\$character_id .'?aggr='.\$aggregate)?>\">
                    <i class=\"fa fa-database\"></i> NEW! Market Explorer
                </a>
            </li>
            <li class=\"tradesimulator\">
                <a href=\"<?=base_url('tradesimulator/index/'.\$character_id .'?aggr='.\$aggregate)?>\">
                    <i class=\"fa fa-files-o\"></i> Trade Simulator
                </a>
            </li>
            <li class=\"stocklists\">
                <a href=\"<?=base_url('stocklists/index/'.\$character_id .'?aggr='.\$aggregate)?>\">
                    <i class=\"fa fa-list-ol\"></i> Stock Lists
                </a>
            </li>
            <li class=\"traderoutes\">
                <a href=\"<?=base_url('traderoutes/index/'.\$character_id .'?aggr='.\$aggregate)?>\">
                    <i class=\"fa fa-plane\"></i> Trade Routes
                </a>
            </li>
            <li class=\"nav-category\">
                Options
            </li>
            <li class=\"citadeltax\">
                <a href=\"<?=base_url('citadeltax/index/'.\$character_id .'?aggr='.\$aggregate)?>\">
                    <i class=\"fa fa-eraser\"></i> Citadel Taxes
                </a>
                
            </li>
            <li class=\"settings\">
                <a href=\"<?=base_url('settings/index/'.\$character_id .'?aggr='.\$aggregate)?>\">
                    <i class=\"fa fa-wrench\"></i> Settings
                </a>
                
            </li>
            <li class=\"apikey\">
                <a href=\"<?=base_url('apikeymanagement/index/'.\$character_id .'?aggr='.\$aggregate)?>\">
                    <i class=\"fa fa-users\"></i> API Key Management
                </a>
            </li>
             <li class=\"nav-category\">
                Logout
            </li>
            <li class=\"logout\">
                <a href=\"<?=base_url('logout')?>\">
                    <i class=\"fa fa-sign-out\"></i> Logout
                </a>
            </li>
        </ul>
    </nav>
</aside>
<!-- End navigation-->";
    }

    public function getTemplateName()
    {
        return "common/header_v.php";
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
        return new Twig_Source("<!-- Header-->
<nav class=\"navbar navbar-default navbar-fixed-top\" data-id=\"<?=\$character_id?>\" data-url=\"<?=base_url()?>\">
    <div class=\"container-fluid\">
        <div class=\"navbar-header\">
            <div id=\"mobile-menu\">
                <div class=\"left-nav-toggle\">
                    <a href=\"#\">
                        <i class=\"stroke-hamburgermenu\">
                        </i>
                    </a>
                </div>
            </div>
            <a class=\"navbar-brand nav-u\" data-selected=\"<?=\$selected?>\" href=\"<?=base_url('Updater')?>\">
                ETM
                <span>
                    v.2.0
                </span>
                <i class=\"fa fa-refresh\">
                </i>
            </a>
        </div>
        <div class=\"navbar-collapse collapse\" id=\"navbar\">
            <div class=\"left-nav-toggle\">
                <a href=\"#\">
                    <i class=\"stroke-hamburgermenu\">
                    </i>
                </a>
            </div>
            <ul class=\"nav navbar-nav navbar-right\">
                <li class=\"profil-link\" data-aggr=\"<?=\$aggregate?>\" data-character=\"<?=\$character_id?>\" data-url=\"<?=base_url()?>\">
                    <a href=\"<?=base_url('Updater')?>\">
                        <span class=\"profile-address\">
                            <i class=\"fa fa-balance-scale\">
                            </i>
                            <span class=\"header-balance\" title=\"balance\">
                            </span>
                            |
                            <i class=\"fa fa-briefcase\">
                            </i>
                            <span class=\"header-networth\" title=\"assets\">
                            </span>
                            |
                            <i class=\"pe-7s-cart\">
                            </i>
                            <span class=\"header-orders\" title=\"sell orders\">
                            </span>
                            |
                            <i class=\"pe-7s-culture\">
                            </i>
                            <span class=\"header-escrow\" title=\"escrow\">
                            </span>
                        </span>
                        <i class=\"fa fa-refresh\"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- End header-->
<!-- Navigation-->
<aside class=\"navigation\">
    <nav>
        <ul class=\"nav luna-nav\">
            <li class=\"nav-category\">
                Profit Tracking
            </li>
            <li class=\"dashboard\">
                <a href=\"<?=base_url('dashboard/index/'.\$character_id .'?aggr='.\$aggregate)?>\">
                     <i class=\"fa fa-tachometer\"></i> Dashboard
                </a>
            </li>
            <li class=\"profits\">
                <a href=\"<?=base_url('profits/index/'.\$character_id .'?aggr='.\$aggregate)?>\">
                    <i class=\"fa fa-line-chart\"></i> Profit Breakdown
                </a>
            </li>
            <li class=\"statistics\">
                <a href=\"<?=base_url('statistics/index/'.\$character_id .'?aggr='.\$aggregate)?>\">
                    <i class=\"fa fa-area-chart\"></i> Statistics
                </a>
            </li>
            <li class=\"nav-category\">
                Archive
            </li>
            <li class=\"transactions\">
                <a href=\"<?=base_url('transactions/index/'.\$character_id .'?aggr='.\$aggregate)?>\">
                    <i class=\"fa fa-list-ol\"></i> Transactions
                </a>
            </li>
            <li class=\"marketorders\">
                <a href=\"<?=base_url('marketorders/index/'.\$character_id .'?aggr='.\$aggregate)?>\">
                    <i class=\"fa fa-shopping-cart\"></i> Market Orders
                </a>
            </li>
            <li class=\"contracts\">
                <a href=\"<?=base_url('contracts/index/'.\$character_id .'?aggr='.\$aggregate)?>\">
                    <i class=\"fa fa-files-o\"></i> Contracts
                </a>
            </li>
            <li class=\"nav-category\">
                Net Worth Evaluation
            </li>
            <li class=\"assets\">
                <a href=\"<?=base_url('assets/index/'.\$character_id .'?aggr='.\$aggregate)?>\">
                    <i class=\"fa fa-suitcase\"></i> Assets
                </a>
            </li>
            <li class=\"networth\">
                <a href=\"<?=base_url('networthtracker/index/'.\$character_id .'?aggr='.\$aggregate)?>\">
                    <i class=\"fa fa-bar-chart\"></i> Net worth Tracker
                </a>
            </li>
            <li class=\"nav-category\">
                <i class=\"fa fa-magic\"></i> Trade Assistant
            </li>
            <li class=\"marketexplorer\">
                <a href=\"<?=base_url('marketexplorer/index/'.\$character_id .'?aggr='.\$aggregate)?>\">
                    <i class=\"fa fa-database\"></i> NEW! Market Explorer
                </a>
            </li>
            <li class=\"tradesimulator\">
                <a href=\"<?=base_url('tradesimulator/index/'.\$character_id .'?aggr='.\$aggregate)?>\">
                    <i class=\"fa fa-files-o\"></i> Trade Simulator
                </a>
            </li>
            <li class=\"stocklists\">
                <a href=\"<?=base_url('stocklists/index/'.\$character_id .'?aggr='.\$aggregate)?>\">
                    <i class=\"fa fa-list-ol\"></i> Stock Lists
                </a>
            </li>
            <li class=\"traderoutes\">
                <a href=\"<?=base_url('traderoutes/index/'.\$character_id .'?aggr='.\$aggregate)?>\">
                    <i class=\"fa fa-plane\"></i> Trade Routes
                </a>
            </li>
            <li class=\"nav-category\">
                Options
            </li>
            <li class=\"citadeltax\">
                <a href=\"<?=base_url('citadeltax/index/'.\$character_id .'?aggr='.\$aggregate)?>\">
                    <i class=\"fa fa-eraser\"></i> Citadel Taxes
                </a>
                
            </li>
            <li class=\"settings\">
                <a href=\"<?=base_url('settings/index/'.\$character_id .'?aggr='.\$aggregate)?>\">
                    <i class=\"fa fa-wrench\"></i> Settings
                </a>
                
            </li>
            <li class=\"apikey\">
                <a href=\"<?=base_url('apikeymanagement/index/'.\$character_id .'?aggr='.\$aggregate)?>\">
                    <i class=\"fa fa-users\"></i> API Key Management
                </a>
            </li>
             <li class=\"nav-category\">
                Logout
            </li>
            <li class=\"logout\">
                <a href=\"<?=base_url('logout')?>\">
                    <i class=\"fa fa-sign-out\"></i> Logout
                </a>
            </li>
        </ul>
    </nav>
</aside>
<!-- End navigation-->", "common/header_v.php", "C:\\xampp\\htdocs\\v2\\application\\views\\common\\header_v.php");
    }
}
