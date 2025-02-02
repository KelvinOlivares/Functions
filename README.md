# Manipuladores de Documentos Brasileiros

## Descrição

Este projeto fornece um conjunto de classes PHP para manipulação e validação de documentos e números de telefone brasileiros. Atualmente, inclui manipuladores para CNPJ (Cadastro Nacional da Pessoa Jurídica), CPF (Cadastro de Pessoas Físicas) e números de telefone brasileiros.

### CNPJHandler

A classe `CNPJHandler` oferece métodos para validar, formatar e gerar números de CNPJ.

Métodos principais:
- `validate($cnpj)`: Valida um número de CNPJ.
- `format($cnpj)`: Formata um número de CNPJ.
- `generate($formatted = false)`: Gera um número de CNPJ válido.
- `process($cnpj, $validate = true, $format = true)`: Processa um CNPJ (valida e formata).

### CPFHandler

A classe `CPFHandler` oferece métodos para validar, formatar, mascarar e gerar números de CPF.

Métodos principais:
- `validate($cpf)`: Valida um número de CPF.
- `format($cpf)`: Formata um número de CPF.
- `mask($cpf)`: Mascara um número de CPF para privacidade.
- `generate($formatted = false)`: Gera um número de CPF válido.
- `process($cpf, $validate = true, $format = true, $mask = false)`: Processa um CPF (valida, formata e opcionalmente mascara).

### PhoneHandler

A classe `PhoneHandler` oferece métodos para validar, formatar e gerar números de telefone brasileiros.

Métodos principais:
- `validate($phone)`: Valida um número de telefone brasileiro.
- `format($phone, $format = 'international')`: Formata um número de telefone.
- `generate($formatted = false)`: Gera um número de telefone brasileiro válido.
- `parse($phone)`: Analisa um número de telefone em seus componentes.
- `process($phone, $validate = true, $format = 'international')`: Processa um número de telefone (valida, formata e analisa).

## Exemplos

### Exemplo de uso do CNPJHandler

```php
$cnpj = '12.345.678/0001-95';
$result = CNPJHandler::process($cnpj);

if ($result) {
    echo "CNPJ válido: " . $result['formatted'];
} else {
    echo "CNPJ inválido";
}

$randomCNPJ = CNPJHandler::generate(true);
echo "CNPJ aleatório gerado: " . $randomCNPJ;
```

### Exemplo de uso do CPFHandler

```php
$cpf = '123.456.789-09';
$result = CPFHandler::process($cpf, true, true, true);

if ($result) {
    echo "CPF válido: " . $result['formatted'];
    echo "CPF mascarado: " . $result['masked'];
} else {
    echo "CPF inválido";
}

$randomCPF = CPFHandler::generate(true);
echo "CPF aleatório gerado: " . $randomCPF;
```

### Exemplo de uso do PhoneHandler

```php
$phone = '55 82 996484440';
$result = PhoneHandler::process($phone);

if ($result) {
    echo "Número válido: " . $result['formatted'];
    echo "É celular: " . ($result['parsed']['is_mobile'] ? 'Sim' : 'Não');
} else {
    echo "Número de telefone inválido";
}

$randomPhone = PhoneHandler::generate(true);
echo "Número de telefone aleatório gerado: " . $randomPhone;
```

