<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* @KnpPaginator/Pagination/filtration.html.twig */
class __TwigTemplate_ca1731e66576d0e07c9360cc4ba0b1df extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@KnpPaginator/Pagination/filtration.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@KnpPaginator/Pagination/filtration.html.twig"));

        // line 1
        echo "<form method=\"get\" action=\"";
        echo twig_escape_filter($this->env, (isset($context["action"]) || array_key_exists("action", $context) ? $context["action"] : (function () { throw new RuntimeError('Variable "action" does not exist.', 1, $this->source); })()), "html", null, true);
        echo "\" enctype=\"application/x-www-form-urlencoded\">

    ";
        // line 3
        if ((twig_length_filter($this->env, (isset($context["fields"]) || array_key_exists("fields", $context) ? $context["fields"] : (function () { throw new RuntimeError('Variable "fields" does not exist.', 3, $this->source); })())) > 1)) {
            // line 4
            echo "        <select name=\"";
            echo twig_escape_filter($this->env, (isset($context["filterFieldName"]) || array_key_exists("filterFieldName", $context) ? $context["filterFieldName"] : (function () { throw new RuntimeError('Variable "filterFieldName" does not exist.', 4, $this->source); })()), "html", null, true);
            echo "\">
            ";
            // line 5
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["fields"]) || array_key_exists("fields", $context) ? $context["fields"] : (function () { throw new RuntimeError('Variable "fields" does not exist.', 5, $this->source); })()));
            foreach ($context['_seq'] as $context["field"] => $context["label"]) {
                // line 6
                echo "                <option value=\"";
                echo twig_escape_filter($this->env, $context["field"], "html", null, true);
                echo "\"";
                if (((isset($context["selectedField"]) || array_key_exists("selectedField", $context) ? $context["selectedField"] : (function () { throw new RuntimeError('Variable "selectedField" does not exist.', 6, $this->source); })()) == $context["field"])) {
                    echo " selected=\"selected\"";
                }
                echo ">";
                echo twig_escape_filter($this->env, $context["label"], "html", null, true);
                echo "</option>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['field'], $context['label'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 8
            echo "        </select>
    ";
        } else {
            // line 10
            echo "        <input type=\"hidden\" name=\"";
            echo twig_escape_filter($this->env, (isset($context["filterFieldName"]) || array_key_exists("filterFieldName", $context) ? $context["filterFieldName"] : (function () { throw new RuntimeError('Variable "filterFieldName" does not exist.', 10, $this->source); })()), "html", null, true);
            echo "\" value=\"";
            echo twig_escape_filter($this->env, twig_first($this->env, twig_get_array_keys_filter((isset($context["fields"]) || array_key_exists("fields", $context) ? $context["fields"] : (function () { throw new RuntimeError('Variable "fields" does not exist.', 10, $this->source); })()))), "html", null, true);
            echo "\" />
    ";
        }
        // line 12
        echo "    ";
        if ((twig_length_filter($this->env, (isset($context["params"]) || array_key_exists("params", $context) ? $context["params"] : (function () { throw new RuntimeError('Variable "params" does not exist.', 12, $this->source); })())) > 0)) {
            // line 13
            echo "        ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["params"]) || array_key_exists("params", $context) ? $context["params"] : (function () { throw new RuntimeError('Variable "params" does not exist.', 13, $this->source); })()));
            foreach ($context['_seq'] as $context["param"] => $context["value"]) {
                // line 14
                echo "            <input type=\"hidden\" name=\"";
                echo twig_escape_filter($this->env, $context["param"], "html", null, true);
                echo "\" value=\"";
                echo twig_escape_filter($this->env, $context["value"], "html", null, true);
                echo "\"/>
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['param'], $context['value'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 16
            echo "    ";
        }
        // line 17
        echo "    <input type=\"text\" value=\"";
        echo twig_escape_filter($this->env, (isset($context["selectedValue"]) || array_key_exists("selectedValue", $context) ? $context["selectedValue"] : (function () { throw new RuntimeError('Variable "selectedValue" does not exist.', 17, $this->source); })()), "html", null, true);
        echo "\" name=\"";
        echo twig_escape_filter($this->env, (isset($context["filterValueName"]) || array_key_exists("filterValueName", $context) ? $context["filterValueName"] : (function () { throw new RuntimeError('Variable "filterValueName" does not exist.', 17, $this->source); })()), "html", null, true);
        echo "\" />

    <button>";
        // line 19
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["options"]) || array_key_exists("options", $context) ? $context["options"] : (function () { throw new RuntimeError('Variable "options" does not exist.', 19, $this->source); })()), "button", [], "any", false, false, false, 19), "html", null, true);
        echo "</button>

</form>
";
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "@KnpPaginator/Pagination/filtration.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable()
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo()
    {
        return array (  117 => 19,  109 => 17,  106 => 16,  95 => 14,  90 => 13,  87 => 12,  79 => 10,  75 => 8,  60 => 6,  56 => 5,  51 => 4,  49 => 3,  43 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<form method=\"get\" action=\"{{ action }}\" enctype=\"application/x-www-form-urlencoded\">

    {% if fields|length > 1 %}
        <select name=\"{{ filterFieldName }}\">
            {% for field, label in fields %}
                <option value=\"{{ field }}\"{% if selectedField == field %} selected=\"selected\"{% endif %}>{{ label }}</option>
            {% endfor %}
        </select>
    {% else %}
        <input type=\"hidden\" name=\"{{ filterFieldName }}\" value=\"{{ fields|keys|first }}\" />
    {% endif %}
    {% if params|length > 0 %}
        {% for param, value in params %}
            <input type=\"hidden\" name=\"{{ param }}\" value=\"{{ value }}\"/>
        {% endfor %}
    {% endif %}
    <input type=\"text\" value=\"{{ selectedValue }}\" name=\"{{ filterValueName }}\" />

    <button>{{ options.button }}</button>

</form>
", "@KnpPaginator/Pagination/filtration.html.twig", "/app/vendor/knplabs/knp-paginator-bundle/templates/Pagination/filtration.html.twig");
    }
}
