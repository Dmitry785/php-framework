<?php

namespace Framework\Routing;

class Request{
    private array $pathVariables = [];
    public function method() : string{
        return $_SERVER["REQUEST_METHOD"] ?? "GET";
    }
    public function path() : string{
        return parse_url($_SERVER["REQUEST_URI"] ?? "/", PHP_URL_PATH) ?: "/";
    }
    public function query(?string $key = null): mixed{
        return $key === null ? $_GET : ($_GET[$key] ?? null);
    }
    public function input(?string $key = null): mixed{
        $data = json_decode(file_get_contents("php://input", true), true) ?? [];
        $data = array_replace($_POST, $data);
        return $key === null ? $data : ($data[$key] ?? null);
    }
    public function putPathVariables(array $pathVariables): void{
        $this->pathVariables = array_merge($this->pathVariables, $pathVariables);
    }
    public function pathVariable(?string $key = null): mixed{
        return $key === null ? $this->pathVariables : ($this->pathVariables[$key] ?? null);
    }
}