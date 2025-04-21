<?php

namespace ScoobEco\Enum;

enum ErrorType: string
{
    case Database   = 'Erro de Banco de Dados';
    case Validation = 'Validation Error';
    case NotFound   = 'Not Found';
    case Internal   = 'Internal Error';
}
