<?php

class PhoneHandler {
    /**
     * Valida um número de telefone
     *
     * @param string $phone O número de telefone a ser validado
     * @return bool Verdadeiro se válido, falso caso contrário
     */
    public static function validate($phone) {
        $phone = self::clean($phone);
        
        // Formato básico: código de país de 2 dígitos + código de área de 2 dígitos + número de 8-9 dígitos
        if (!preg_match('/^55\d{2}9?\d{8}$/', $phone)) {
            return false;
        }
        
        // Verifica se o código de área é válido (simplificado, não exaustivo)
        $areaCode = substr($phone, 2, 2);
        $validAreaCodes = ['11', '21', '31', '41', '51', '61', '71', '81', '82', '83', '84', '85', '86', '91', '92', '95', '96', '97', '98', '99'];
        
        return in_array($areaCode, $validAreaCodes);
    }

    /**
     * Formata um número de telefone
     *
     * @param string $phone O número de telefone a ser formatado
     * @param string $format O formato desejado ('international', 'national', ou 'local')
     * @return string Número de telefone formatado ou entrada original se inválido
     */
    public static function format($phone, $format = 'international') {
        $phone = self::clean($phone);
        
        if (!self::validate($phone)) {
            return $phone; // Retorna o original se inválido
        }
        
        $countryCode = substr($phone, 0, 2);
        $areaCode = substr($phone, 2, 2);
        $number = substr($phone, 4);
        
        switch ($format) {
            case 'international':
                return "+{$countryCode} ({$areaCode}) " . substr($number, 0, 5) . '-' . substr($number, 5);
            case 'national':
                return "({$areaCode}) " . substr($number, 0, 5) . '-' . substr($number, 5);
            case 'local':
                return substr($number, 0, 5) . '-' . substr($number, 5);
            default:
                return $phone;
        }
    }

    /**
     * Limpa um número de telefone removendo todos os caracteres não numéricos
     *
     * @param string $phone O número de telefone a ser limpo
     * @return string Número de telefone limpo
     */
    public static function clean($phone) {
        return preg_replace('/[^0-9]/', '', (string) $phone);
    }

    /**
     * Gera um número de telefone brasileiro válido aleatório
     *
     * @param bool $formatted Se deve retornar o número de telefone formatado ou não
     * @return string Um número de telefone brasileiro válido aleatório
     */
    public static function generate($formatted = false) {
        $validAreaCodes = ['11', '21', '31', '41', '51', '61', '71', '81', '82', '83', '84', '85', '86', '91', '92', '95', '96', '97', '98', '99'];
        $areaCode = $validAreaCodes[array_rand($validAreaCodes)];
        $number = '9' . mt_rand(10000000, 99999999);
        
        $phone = '55' . $areaCode . $number;
        
        return $formatted ? self::format($phone) : $phone;
    }

    /**
     * Analisa um número de telefone em seus componentes
     *
     * @param string $phone O número de telefone a ser analisado
     * @return array|false Componentes analisados ou falso se inválido
     */
    public static function parse($phone) {
        $phone = self::clean($phone);
        
        if (!self::validate($phone)) {
            return false;
        }
        
        return [
            'country_code' => substr($phone, 0, 2),
            'area_code' => substr($phone, 2, 2),
            'number' => substr($phone, 4),
            'is_mobile' => (substr($phone, 4, 1) == '9'),
        ];
    }

    /**
     * Função abrangente de processamento de número de telefone
     *
     * @param string $phone O número de telefone a ser processado
     * @param bool $validate Se deve validar o número de telefone
     * @param string $format O formato desejado ('international', 'national', ou 'local')
     * @return array|false Resultado do processamento ou falso se inválido
     */
    public static function process($phone, $validate = true, $format = 'international') {
        $cleaned = self::clean($phone);
        
        if ($validate && !self::validate($cleaned)) {
            return false;
        }
        
        $result = [
            'original' => $phone,
            'cleaned' => $cleaned,
            'valid' => self::validate($cleaned),
            'formatted' => self::format($cleaned, $format),
            'parsed' => self::parse($cleaned),
        ];
        
        return $result;
    }
}

// Exemplo de uso
$phone = '55 82 996484440';
$result = PhoneHandler::process($phone);

if ($result) {
    echo "Número de telefone processado com sucesso:\n";
    echo "Original: " . $result['original'] . "\n";
    echo "Limpo: " . $result['cleaned'] . "\n";
    echo "Válido: " . ($result['valid'] ? 'Sim' : 'Não') . "\n";
    echo "Formatado: " . $result['formatted'] . "\n";
    echo "Componentes:\n";
    echo "  Código do País: " . $result['parsed']['country_code'] . "\n";
    echo "  Código de Área: " . $result['parsed']['area_code'] . "\n";
    echo "  Número: " . $result['parsed']['number'] . "\n";
    echo "  É celular: " . ($result['parsed']['is_mobile'] ? 'Sim' : 'Não') . "\n";
} else {
    echo "Número de telefone inválido\n";
}

// Gerar um número de telefone aleatório
$randomPhone = PhoneHandler::generate(true);
echo "Número de telefone aleatório gerado: " . $randomPhone . "\n";

?>
