<?php

namespace Framework\Routing;

class TemplatePathRegexCompiler {
    public function compile(string $templatePath) : string {
        $regex = preg_replace_callback(
            '~\{(\w+)\}|([^{}]+)~',
            fn($m) => $m[1] !== ''
                ? '(?<' . $m[1] . '>[a-zA-Z_\-0-9]+)'
                : preg_quote($m[2], '~'),
            $templatePath
        );

        return '^' . $regex . '$';
    }
}