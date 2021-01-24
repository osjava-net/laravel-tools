<?php namespace QFrame\Support\Template;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TemplateHelper
{
    const FORMAT_SEPARATOR = ':';
    const FILTER_SEPARATOR = '|';

    private const FILTER_LIST = [
        DatetimeFilter::NAME => DatetimeFilter::class,
        TimestampFilter::NAME => TimestampFilter::class,
    ];

    public static function parse($template, $source) {
        if(empty($template) || empty($source)) return array();

        $variables = self::parseVariable($template);
        return self::parseToVariable($template, $variables, $source);
    }

    public static function render($template, $data) {
        if (empty($data)) return $template;

        $variables = self::parseVariable($template);

        if (empty($variables)) return $template;

        return self::parseToString($template, $variables, $data);
    }

    private static function parseVariable($template) {
        if (!preg_match_all("/\{[^{]+\}/", $template, $matches)) {
            return $template;
        }
        Log::debug("Parse the variable from the template[$template]: ", $matches);

        if(empty($matches)) return array();

        $variables = array();
        foreach (head($matches) as $expression) {
            $variable = new Variable($expression);

            $terms = preg_replace('/[{}]/', '', $expression);
            $terms = explode('|', $terms);

            $firstTerms = explode(':', array_shift($terms));
            $variable->name = $firstTerms[0];
            if (count($firstTerms) > 1) {
                $variable->format = $firstTerms[1];
            }

            if (!empty($terms)) {
                $filters = array();
                foreach ($terms as $term) {
                    $filterTerms = explode(':', $term);
                    if (count($filterTerms) > 1) {
                        $filters[$filterTerms[0]] = explode(',', $filterTerms[1]);
                    } else {
                        $filters[$filterTerms[0]] = [];
                    }
                }

                $variable->filters = $filters;
            }

            Log::debug("Split the expression[$expression]: ", ['terms' => $terms, 'variable' => $variable->jsonSerialize()]);
            $variables[] = $variable;
        }

        return $variables;
    }

    /**
     * @param string $template
     * @param array $variables
     * @param array $data
     * @return string
     */
    private static function parseToString($template, $variables, $data) {
        Log::debug("Parse template [$template] with: ", [...$variables, $data]);

        /** @var Variable $variable */
        foreach ($variables as $variable) {
            $value = Arr::get($data, $variable->name);
            if ($value instanceof Carbon && !empty($variable->format)) {
                $value = $value->format($variable->format);
            }

            if (!empty($variable->filters)) {
                foreach ($variable->filters as $fKey => $fValue) {
                    /** @var VariableFilter $filter */
                    $filter = resolve(Arr::get(self::FILTER_LIST, $fKey));
                    $value = $filter->filter($value, ...$fValue);
                }
            }

            $template = Str::replaceFirst($variable->expression, $value, $template);
        }

        Log::debug("Template is rendered to a string: $template");
        return $template;
    }

    private static function parseToVariable($template, $variables, $source) {
        Log::debug("Parse $template to regex: ", $variables);

        $names = [];
        /** @var Variable $variable */
        foreach ($variables as $variable) {
            $format = $variable->format ?: '[0-9a-zA-Z]';
            $pattern = ($format === '[*]') ? "(.*)" : "($format+)";
            $template = str_replace($variable->expression, $pattern, $template);
            $names[] = $variable->name;
        }

        $pattern = "/^$template$/i";
        Log::debug("Template to Regex: $pattern");
        preg_match($pattern, $source, $matches);
        if(empty($matches)) return array();

        $result = [];
        foreach ($names as $key => $name) {
            $result[$name] = $matches[$key + 1];
        }

        Log::debug("Matches variables:", $result);
        return $result;
    }
}
