<?php

class CNPJHandler {
    /**
     * Valida um número de CNPJ
     *
     * @param string $cnpj O número de CNPJ a ser validado
     * @return bool Verdadeiro se válido, falso caso contrário
     */
    public static function validate($cnpj) {
        // Remove quaisquer caracteres não numéricos
        $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);

        // Verifica se tem 14 dígitos
        if (strlen($cnpj) != 14) {
            return false;
        }

        // Verifica por sequências inválidas conhecidas
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        // Valida dígitos verificadores
        for ($t = 12; $t < 14; $t++) {
            $d = 0;
            $c = 0;
            for ($i = $t - 7; $i >= 2; $i--, $c++) {
                $d += $cnpj[$c] * $i;
            }
            for ($i = 9; $i >= 2; $i--, $c++) {
                $d += $cnpj[$c] * $i;
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cnpj[$t] != $d) {
                return false;
            }
        }

        return true;
    }

    /**
     * Formata um número de CNPJ
     *
     * @param string $cnpj O número de CNPJ a ser formatado
     * @return string CNPJ formatado ou entrada original se inválido
     */
    public static function format($cnpj) {
        $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);

        if (strlen($cnpj) != 14) {
            return $cnpj; // Retorna o original se não tiver 14 dígitos
        }

        return sprintf(
            '%s.%s.%s/%s-%s',
            substr($cnpj, 0, 2),
            substr($cnpj, 2, 3),
            substr($cnpj, 5, 3),
            substr($cnpj, 8, 4),
            substr($cnpj, 12, 2)
        );
    }

    /**
     * Limpa um número de CNPJ removendo todos os caracteres não numéricos
     *
     * @param string $cnpj O número de CNPJ a ser limpo
     * @return string Número de CNPJ limpo
     */
    public static function clean($cnpj) {
        return preg_replace('/[^0-9]/', '', (string) $cnpj);
    }

    /**
     * Gera um número de CNPJ válido aleatório
     *
     * @param bool $formatted Se deve retornar o CNPJ formatado ou não
     * @return string Um número de CNPJ válido aleatório
     */
    public static function generate($formatted = false) {
        $base = sprintf('%014d', mt_rand(0, 99999999999999));

        $base = substr($base, 0, 12);

        for ($i = 0; $i < 2; $i++) {
            $sum = 0;
            $pos = 5 + $i;
            for ($j = 0; $j < 12 + $i; $j++) {
                $sum += $base[$j] * $pos;
                $pos = ($pos == 2) ? 9 : $pos - 1;
            }
            $base .= ((($sum % 11) < 2) ? 0 : (11 - ($sum % 11)));
        }

        return $formatted ? self::format($base) : $base;
    }

    /**
     * Função abrangente de processamento de CNPJ
     *
     * @param string $cnpj O número de CNPJ a ser processado
     * @param bool $validate Se deve validar o CNPJ
     * @param bool $format Se deve formatar o CNPJ
     * @return array|false Resultado do processamento ou falso se inválido
     */
    public static function process($cnpj, $validate = true, $format = true) {
        $cleaned = self::clean($cnpj);

        if ($validate && !self::validate($cleaned)) {
            return false;
        }

        $result = [
            'original' => $cnpj,
            'cleaned' => $cleaned,
            'valid' => self::validate($cleaned),
        ];

        if ($format) {
            $result['formatted'] = self::format($cleaned);
        }

        return $result;
    }
}

// Exemplo de uso
$cnpj = '12.345.678/0001-95';
$result = CNPJHandler::process($cnpj);

if ($result) {
    echo "CNPJ processado com sucesso:\n";
    echo "Original: " . $result['original'] . "\n";
    echo "Limpo: " . $result['cleaned'] . "\n";
    echo "Válido: " . ($result['valid'] ? 'Sim' : 'Não') . "\n";
    echo "Formatado: " . $result['formatted'] . "\n";
} else {
    echo "CNPJ inválido\n";
}

// Gerar um CNPJ aleatório
$randomCNPJ = CNPJHandler::generate(true);
echo "CNPJ aleatório gerado: " . $randomCNPJ . "\n";

?>
