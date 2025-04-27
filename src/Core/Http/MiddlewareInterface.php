<?php

namespace ScoobEco\Core\Http;

interface MiddlewareInterface
{
    public function handle(Request $request);
}