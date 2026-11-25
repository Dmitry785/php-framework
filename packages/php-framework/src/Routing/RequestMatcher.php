<?php

namespace Framework\Routing;

interface RequestMatcher{
    function match(Request $request): bool;
}