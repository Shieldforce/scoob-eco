<?php

namespace ScoobEco\Eco\Services\Scoob;

use ScoobEco\Core\Http\Request;
use ScoobEco\Eco\Enum\SetupScoobType;

class StepInstallationScoobService
{
    private string $message;

    public function __construct(
        public Request $request,
        public SetupScoobType $typeStep
    ) {}

    public function run(): self
    {
        $this->setup();
        return $this;
    }

    public function message()
    {
        return $this->message;
    }

    public function setup() {
        $class = $this->typeStep->classNew();
        $classNew = new $class($this->request, $this->typeStep);
        $classNew->setup();
        $this->message = $classNew->message();
    }
}