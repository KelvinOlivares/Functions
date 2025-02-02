<?php

class CPFHandler {
    /**
     * Valida um número de CPF
     *
     * @param string $cpf O número de CPF a ser validado
     * @return bool Verdadeiro se válido, falso caso contrário
     */
    public static function validate($cpf) {
        // Remove quaisquer caracteres não numéricos
        $cpf = preg_replace('/[^0-9]/', '', (string) $cpf);

        // Verifica se tem 11 dígitos
        if (strlen($cpf) != 11) {
            return false;
        }

        // Verifica por sequências inválidas conhecidas
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Valida dígitos verificadores
        for ($t = 9; $t < 11; $t++) {
            $d = 0;
            for ($c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$t] != $d) {
                return false;
            }
        }

        return true;
    }

    /**
     * Formata um número de CPF
     *
     * @param string $cpf O número de CPF a ser formatado
     * @return string CPF formatado ou entrada original se inválido
     */
    public static function format($cpf) {
        $cpf = preg_replace('/[^0-9]/', '', (string) $cpf);

        if (strlen($cpf) != 11) {
            return $cpf; // Retorna o original se não tiver 11 dígitos
        }

        return sprintf(
            '%s.%s.%s-%s',
            substr($cpf, 0, 3),
            substr($cpf, 3, 3),
            substr($cpf, 6, 3),
            substr($cpf, 9, 2)
        );
    }

    /**
     * Limpa um número de CPF removendo todos os caracteres não numéricos
     *
     * @param string $cpf O número de CPF a ser limpo
     * @return string Número de CPF limpo
     */
    public static function clean($cpf) {
        return preg_replace('/[^0-9]/', '', (string) $cpf);
    }

    /**
     * Gera um número de CPF válido aleatório
     *
     * @param bool $formatted Se deve retornar o CPF formatado ou não
     * @return string Um número de CPF válido aleatório
     */
    public static function generate($formatted = false) {
        $base = sprintf('%09d', mt_rand(0, 999999999));

        for ($i = 0; $i < 2; $i++) {
            $sum = 0;
            for ($j = 0; $j < 9 + $i; $j++) {
                $sum += $base[$j] * (10 + $i - $j);
            }
            $base .= ((($sum % 11) < 2) ? 0 : (11 - ($sum % 11)));
        }

        return $formatted ? self::format($base) : $base;
    }

    /**
     * Mascara um número de CPF para privacidade
     *
     * @param string $cpf O número de CPF a ser mascarado
     * @return string Número de CPF mascarado
     */
    public static function mask($cpf) {
        $cpf = self::clean($cpf);
        if (strlen($cpf) != 11) {
            return $cpf;
        }
        return substr($cpf, 0, 3) . '.XXX.XXX-' . substr($cpf, -2);
    }

    /**
     * Função abrangente de processamento de CPF
     *
     * @param string $cpf O número de CPF a ser processado
     * @param bool $validate Se deve validar o CPF
     * @param bool $format Se deve formatar o CPF
     * @param bool $mask Se deve mascarar o CPF
     * @return array|false Resultado do processamento ou falso se inválido
     */
    public static function process($cpf, $validate = true, $format = true, $mask = false) {
        $cleaned = self::clean($cpf);

        if ($validate && !self::validate($cleaned)) {
            return false;
        }

        $result = [
            'original' => $cpf,
            'cleaned' => $cleaned,
            'valid' => self::validate($cleaned),
        ];

        if ($format) {
            $result['formatted'] = self::format($cleaned);
        }

        if ($mask) {
            $result['masked'] = self::mask($cleaned);
        }

        return $result;
    }
}

// Exemplo de uso
$cpf = '123.456.789-09';
$result = CPFHandler::process($cpf, true, true, true);

if ($result) {
    echo "CPF processado com sucesso:\n";
    echo "Original: " . $result['original'] . "\n";
    echo "Limpo: " . $result['cleaned'] . "\n";
    echo "Válido: " . ($result['valid'] ? 'Sim' : 'Não') . "\n";
    echo "Formatado: " . $result['formatted'] . "\n";
    echo "Mascarado: " . $result['masked'] . "\n";
} else {
    echo "CPF inválido\n";
}

// Gerar um CPF aleatório
$randomCPF = CPFHandler::generate(true);
echo "CPF aleatório gerado: " . $randomCPF . "\n";

?>
