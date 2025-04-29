<?php

namespace ScoobEco\Eco\Services\Scoob;

use ScoobEco\Core\Http\Request;
use ScoobEco\Eco\Enum\SetupScoobType;

interface SetupScoobInterfaceService
{
    public function __construct(
        Request        $request,
        SetupScoobType $typeStep,
    );

    public function setup();

    public function message(): string;
}