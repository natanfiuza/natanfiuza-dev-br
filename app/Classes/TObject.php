<?php

namespace App\Classes;

use DateTime;
use SimpleXMLElement;

/**
 * Classe principal de um objeto, com funcoes necessarias para controle.
 *
 * @since 10/07/2006
 * @author        Nataniel Fiuza <natan.fiuza@gmail.com>
 * @version     1.1 7/8/2006
 * @package    system
 * @copyright
 *
 */
class TObject
{

    /**
     * Utilizado na funcao <b>validEmailAdd</b> recebe mensagem de erro quando return for false
     * @var String
     */
    public $mailERROR;

    /**
     * Converte a data.
     * Do formato mysql "yyyy-mm-dd" para o formato "dd/mm/yyyy"
     *
     * @return string . Retorna a data no formato dd/mm/yyyy
     * @access public
     */
    public function convData($pDataMysql)
    {
        $tdata = array('', '', '');
        if (strpos($pDataMysql, "-") > 0) {
            $tdata = explode("-", $pDataMysql);
        }
        return $tdata[2] . "/" . $tdata[1] . "/" . $tdata[0];
    }

    /**
     * Converte a data.
     * Do formato "dd/mm/yyyy" para o formato mysql "yyyy-mm-dd"
     *
     * @return string . Retorna a data no formato dd/mm/yyyy
     * @access public
     */
    public function convDataMysql($pData)
    {
        list($dia, $mes, $ano) = explode("/", $pData);
        return $ano . "-" . $mes . "-" . $dia;
    }

    /**
     * Converte a data.
     * Do formato "dd/mm/yyyy" para o formato mysql "mm/dd/aaaa"
     *
     * @return string . Retorna a data no formato dd/mm/yyyy
     * @access public
     */
    public function convDataMssql($pData)
    {
        list($dia, $mes, $ano) = explode("/", $pData);
        return $mes . "/" . $dia . "/" . $ano;
    }

    /**
     * Converte a data.
     * Do formato "dd/mm/yyyy" para o formato mysql "dd/mm"
     *
     * @return string . Retorna a data no formato dd/mm
     * @access public
     */
    public function convDataMysqlDayM($pData)
    {
        list($ano, $mes, $dia) = explode("-", $pData);
        return $dia . "/" . $mes;
    }

    /**
     * Soma uma quantidade de dias a uma data passada
     *
     * @return string - Data no format dd/mm/yyyy
     * @access public
     */
    public function sumData($pData, $pDias = 1)
    {
        list($dia, $mes, $ano) = explode("/", $pData);
        $date = new DateTime($ano . "-" . $mes . "-" . $dia);
        $date->modify("+" . $pDias . " day");
        return $date->format("d/m/Y");
    }

    /**
     *
     * @return string
     * @access public
     */
    public function strip_tags_except($text, $allowed_tags, $strip = true)
    {
        if (!is_array($allowed_tags)) {
            return $text;
        }

        if (!count($allowed_tags)) {
            return $text;
        }

        $open = $strip ? '' : '&lt;';
        $close = $strip ? '' : '&gt;';

        preg_match_all('!<\s*(/)?\s*([a-zA-Z]+)[^>]*>!', $text, $all_tags);
        array_shift($all_tags);
        $slashes = $all_tags[0];
        $all_tags = $all_tags[1];
        foreach ($all_tags as $i => $tag) {
            if (in_array($tag, $allowed_tags)) {
                continue;
            }

            $text = preg_replace('!<(\s*' . $slashes[$i] . '\s*' . $tag . '[^>]*)>!', " ", $text);
        }
        return $text;
    }

    /**
     * Recebe uma data do tipo dd/mm/aaaa e retorna de acordo com pStringFormato
     * Exemplo de pStringFormato   "dd/mm","mm - aaaa", "aaaa-mm-dd"
     * dd = dia
     * mm = mes
     * AA = Ano em dois digitos
     * aaaa = ano em quatro digitos
     *
     * O valor de pData pode ser passado nos formatos "dd/mm/aaaa" ou "aaaa-mm-dd"
     *
     * @access public
     */
    public function formataData($pData, $pStringFormato = "dd/mm/aaaa")
    {

        if (strpos($pData, "/") > 0) {
            list($dia, $mes, $ano) = explode("/", $pData);
        }
        if (strpos($pData, "-") > 0) {
            list($ano, $mes, $dia) = explode("-", $pData);
        }
        if (strlen($dia) == 1) {
            $cDia = "0" . $dia;
        } else {
            $cDia = $dia;
        }
        if (strlen($mes) == 1) {
            $cMes = "0" . $mes;
        } else {
            $cMes = $mes;
        }

        $cAno = substr($ano, 3, 2);

        $pStringFormato = str_replace("dd", $cDia, $pStringFormato);
        $pStringFormato = str_replace("mm", $cMes, $pStringFormato);
        $pStringFormato = str_replace("AA", $cAno, $pStringFormato);
        $pStringFormato = str_replace("aaaa", $ano, $pStringFormato);

        return $pStringFormato;
    }


    /**
     * Retorna o objeto passado no formato json, caso não seja informado o proprio
     * @param array $add Array para ser adicionado no objeto
     * @param array $obj Objeto a ser convertido caso nao seja informado $this
     * @return  No formato json
     */
    public function __getJson($add = null, $obj = null)
    {

        $obj == null ? $obj = $this : null;

        unset($obj->nomeTabela);
        unset($obj->dataBase);
        unset($obj->mailERROR);
        if (isset($add)) {
            foreach ($add as $key => $value) {
                $obj->$key = $value;
            }
        }
        $retArray = array(get_class($obj) => $obj);
        return json_encode($retArray);
    }

    /**
     * FormatCurrency(Expression[,NumDigitsAfterDecimal [,IncludeLeadingDigit
     * [,UseParensForNegativeNumbers [,GroupDigits]]]])
     *
     * NumDigitsAfterDecimal is the numeric value indicating how many places to the
     * right of the decimal are displayed
     * -1 Use Default
     *
     * The IncludeLeadingDigit, UseParensForNegativeNumbers, and GroupDigits arguments
     * have the following settings:
     *  -1 True
     *  0 False
     *  -2 Use Default
     *
     * @param double $amount
     * @param int $NumDigitsAfterDecimal
     * @param bool $IncludeLeadingDigit
     * @param bool $UseParensForNegativeNumbers
     * @param bool $GroupDigits
     * @return currency format
     */
    public function FormatCurrency($amount, $NumDigitsAfterDecimal, $IncludeLeadingDigit = -1, $UseParensForNegativeNumbers = -1, $GroupDigits = -1)
    {
        //Export the values returned by localeconv into the local scope
        extract(localeconv());
        if (strpos(" " . $amount, '.') > 0) {
            list($a, $b) = explode('.', $amount);
            if (strlen($b) > 2) {
                $b = substr($b, 0, 2);
            }
            $amount = $a . "." . $b;
        }
        // Set defaults if locale is not set
        if (empty($currency_symbol)) {
            $currency_symbol = env('DEFAULT_CURRENCY_SYMBOL', 'R$');
        }

        if (empty($mon_decimal_point)) {
            $mon_decimal_point = env('DEFAULT_MON_DECIMAL_POINT', ',');
        }

        if (empty($mon_thousands_sep)) {
            $mon_thousands_sep = env('DEFAULT_MON_THOUSANDS_SEP', '.');
        }

        if (empty($positive_sign)) {
            $positive_sign = env('DEFAULT_POSITIVE_SIGN', '+');
        }

        if (empty($negative_sign)) {
            $negative_sign = env('DEFAULT_NEGATIVE_SIGN', '-');
        }

        if ($frac_digits == CHAR_MAX) {
            $frac_digits = env('DEFAULT_FRAC_DIGITS', 2);
        }

        if ($p_cs_precedes == CHAR_MAX) {
            $p_cs_precedes = env('DEFAULT_P_CS_PRECEDES', true);
        }

        if ($p_sep_by_space == CHAR_MAX) {
            $p_sep_by_space = env('DEFAULT_P_SEP_BY_SPACE', false);
        }

        if ($n_cs_precedes == CHAR_MAX) {
            $n_cs_precedes = env('DEFAULT_N_CS_PRECEDES', true);
        }

        if ($n_sep_by_space == CHAR_MAX) {
            $n_sep_by_space = env('DEFAULT_N_SEP_BY_SPACE', false);
        }

        if ($p_sign_posn == CHAR_MAX) {
            $p_sign_posn = env('DEFAULT_P_SIGN_POSN', 3);
        }

        if ($n_sign_posn == CHAR_MAX) {
            $n_sign_posn = env('DEFAULT_N_SIGN_POSN', 3);
        }

        // check $NumDigitsAfterDecimal
        if ($NumDigitsAfterDecimal > -1) {
            $frac_digits = $NumDigitsAfterDecimal;
        }

        // check $UseParensForNegativeNumbers
        if ($UseParensForNegativeNumbers == -1) {
            $n_sign_posn = 0;
            if ($p_sign_posn == 0) {
                if (env('DEFAULT_P_SIGN_POSN', 3) != 0) {
                    $p_sign_posn = env('DEFAULT_P_SIGN_POSN', 3);
                } else {
                    $p_sign_posn = 3;
                }

            }
        } elseif ($UseParensForNegativeNumbers == 0) {
            if ($n_sign_posn == 0) {
                if (env('DEFAULT_P_SIGN_POSN', 3) != 0) {
                    $n_sign_posn = env('DEFAULT_P_SIGN_POSN', 3);
                } else {
                    $n_sign_posn = 3;
                }
            }

        }

        // check $GroupDigits
        if ($GroupDigits == -1) {
            $mon_thousands_sep = env('DEFAULT_MON_THOUSANDS_SEP', '.');
        } elseif ($GroupDigits == 0) {
            $mon_thousands_sep = "";
        }

        // Start by formatting the unsigned number
        $number = number_format(abs($amount), $frac_digits, $mon_decimal_point, $mon_thousands_sep);

        // check $IncludeLeadingDigit
        if ($IncludeLeadingDigit == 0) {
            if (substr($number, 0, 2) == "0.") {
                $number = substr($number, 1, strlen($number) - 1);
            }

        }

        if ($amount < 0) {
            $sign = $negative_sign;
            //The following statements "extracts" the boolean value as an integer
            $n_cs_precedes = intval($n_cs_precedes == true);
            $n_sep_by_space = intval($n_sep_by_space == true);
            $key = $n_cs_precedes . $n_sep_by_space . $n_sign_posn;
        } else {
            $sign = $positive_sign;
            $p_cs_precedes = intval($p_cs_precedes == true);
            $p_sep_by_space = intval($p_sep_by_space == true);
            $key = $p_cs_precedes . $p_sep_by_space . $p_sign_posn;
        }

        $formats = array( // Currency symbol is after amount
            // No space between amount and sign.
            '000' => '(%s' . $currency_symbol . ')', '001' => $sign . '%s ' . $currency_symbol, '002' => '%s' . $currency_symbol . $sign, '003' => '%s' . $sign . $currency_symbol, '004' => '%s' . $sign . $currency_symbol,
            // One space between amount and sign.
            '010' => '(%s ' . $currency_symbol . ')', '011' => $sign . '%s ' . $currency_symbol, '012' => '%s ' . $currency_symbol . $sign, '013' => '%s ' . $sign . $currency_symbol, '014' => '%s ' . $sign . $currency_symbol,
            // Currency symbol is before amount
            // No space between amount and sign.
            '100' => '(' . $currency_symbol . '%s)', '101' => $currency_symbol . $sign . '%s', '102' => $currency_symbol . '%s' . $sign, '103' => $currency_symbol . $sign . '%s', '104' => $currency_symbol . $sign . '%s',
            // One space between amount and sign.
            '110' => '(' . $currency_symbol . ' %s)', '111' => $sign . $currency_symbol . ' %s', '112' => $currency_symbol . ' %s' . $sign, '113' => $sign . $currency_symbol . ' %s', '114' => $currency_symbol . ' ' . $sign . '%s');

        // We then lookup the key in the above array.

        return sprintf($formats[$key], $number);
    }

    /**
     * FormatNumber(Expression[,NumDigitsAfterDecimal [,IncludeLeadingDigit
     * [,UseParensForNegativeNumbers [,GroupDigits]]]])
     *
     * NumDigitsAfterDecimal is the numeric value indicating how many places to the
     * right of the decimal are displayed
     * -1 Use Default
     *
     * The IncludeLeadingDigit, UseParensForNegativeNumbers, and GroupDigits arguments
     * have the following settings:
     * -1 True
     * 0 False
     * -2 Use Default
     *
     * @param double $amount
     * @param int $NumDigitsAfterDecimal
     * @param bool $IncludeLeadingDigit
     * @param bool $UseParensForNegativeNumbers
     * @param bool $GroupDigits
     * @return format number
     */
    public function FormatNumber($amount, $NumDigitsAfterDecimal = 0, $IncludeLeadingDigit = "", $UseParensForNegativeNumbers = "", $GroupDigits = "")
    {
        //Export the values returned by localeconv into the local scope
        extract(localeconv());

        // Set defaults if locale is not set
        if (empty($currency_symbol)) {
            $currency_symbol = env('DEFAULT_CURRENCY_SYMBOL', 'R$');
        }

        if (empty($mon_decimal_point)) {
            $mon_decimal_point = env('DEFAULT_MON_DECIMAL_POINT', ',');
        }

        if (empty($mon_thousands_sep)) {
            $mon_thousands_sep = env('DEFAULT_MON_THOUSANDS_SEP', '.');
        }

        if (empty($positive_sign)) {
            $positive_sign = env('DEFAULT_POSITIVE_SIGN', '+');
        }

        if (empty($negative_sign)) {
            $negative_sign = env('DEFAULT_NEGATIVE_SIGN', '-');
        }

        if ($frac_digits == CHAR_MAX) {
            $frac_digits = env('DEFAULT_FRAC_DIGITS', 2);
        }

        if ($p_cs_precedes == CHAR_MAX) {
            $p_cs_precedes = env('DEFAULT_P_CS_PRECEDES', true);
        }

        if ($p_sep_by_space == CHAR_MAX) {
            $p_sep_by_space = env('DEFAULT_P_SEP_BY_SPACE', false);
        }

        if ($n_cs_precedes == CHAR_MAX) {
            $n_cs_precedes = env('DEFAULT_N_CS_PRECEDES', true);
        }

        if ($n_sep_by_space == CHAR_MAX) {
            $n_sep_by_space = env('DEFAULT_N_SEP_BY_SPACE', false);
        }

        if ($p_sign_posn == CHAR_MAX) {
            $p_sign_posn = env('DEFAULT_P_SIGN_POSN', 3);
        }

        if ($n_sign_posn == CHAR_MAX) {
            $n_sign_posn = env('DEFAULT_N_SIGN_POSN', 3);
        }

        // check $NumDigitsAfterDecimal
        if ($NumDigitsAfterDecimal > -1) {
            $frac_digits = $NumDigitsAfterDecimal;
        }

        // check $UseParensForNegativeNumbers
        if ($UseParensForNegativeNumbers == -1) {
            $n_sign_posn = 0;
            if ($p_sign_posn == 0) {
                if (env('DEFAULT_P_SIGN_POSN', 3) != 0) {
                    $p_sign_posn = env('DEFAULT_P_SIGN_POSN', 3);
                } else {
                    $p_sign_posn = 3;
                }

            }
        } elseif ($UseParensForNegativeNumbers == 0) {
            if ($n_sign_posn == 0) {
                if (env('DEFAULT_P_SIGN_POSN', 3) != 0) {
                    $n_sign_posn = env('DEFAULT_P_SIGN_POSN', 3);
                } else {
                    $n_sign_posn = 3;
                }
            }

        }

        // check $GroupDigits
        if ($GroupDigits == -1) {
            $mon_thousands_sep = env('DEFAULT_MON_THOUSANDS_SEP', '.');
        } elseif ($GroupDigits == 0) {
            $mon_thousands_sep = "";
        }

        // Start by formatting the unsigned number
        $number = number_format(abs($amount), $frac_digits, $mon_decimal_point, $mon_thousands_sep);

        // check $IncludeLeadingDigit
        if ($IncludeLeadingDigit == 0) {
            if (substr($number, 0, 2) == "0.") {
                $number = substr($number, 1, strlen($number) - 1);
            }

        }

        if ($amount < 0) {
            $sign = $negative_sign;
            $key = $n_sign_posn;
        } else {
            $sign = $positive_sign;
            $key = $p_sign_posn;
        }

        $formats = array('0' => '(%s)', '1' => $sign . '%s', '2' => $sign . '%s', '3' => $sign . '%s', '4' => $sign . '%s');

        // We then lookup the key in the above array.
        return sprintf($formats[$key], $number);
    }

    /**
     * FormatPercent(Expression[,NumDigitsAfterDecimal [,IncludeLeadingDigit
     * [,UseParensForNegativeNumbers [,GroupDigits]]]])
     *
     * NumDigitsAfterDecimal is the numeric value indicating how many places to
     * the right of the decimal are displayed
     * -1 Use Default
     *
     * The IncludeLeadingDigit, UseParensForNegativeNumbers, and GroupDigits arguments
     * have the following settings:
     * -1 True
     * 0 False
     * -2 Use Default
     *
     * @param percent number $amount
     * @param int $NumDigitsAfterDecimal
     * @param bool $IncludeLeadingDigit
     * @param bool $UseParensForNegativeNumbers
     * @param bool $GroupDigits
     * @return format percent
     *
     */
    public function FormatPercent($amount, $NumDigitsAfterDecimal, $IncludeLeadingDigit, $UseParensForNegativeNumbers, $GroupDigits)
    {
        //Export the values returned by localeconv into the local scope
        extract(localeconv());

        // Set defaults if locale is not set
        if (empty($currency_symbol)) {
            $currency_symbol = env('DEFAULT_CURRENCY_SYMBOL', 'R$');
        }

        if (empty($mon_decimal_point)) {
            $mon_decimal_point = env('DEFAULT_MON_DECIMAL_POINT', ',');
        }

        if (empty($mon_thousands_sep)) {
            $mon_thousands_sep = env('DEFAULT_MON_THOUSANDS_SEP', '.');
        }

        if (empty($positive_sign)) {
            $positive_sign = env('DEFAULT_POSITIVE_SIGN', '+');
        }

        if (empty($negative_sign)) {
            $negative_sign = env('DEFAULT_NEGATIVE_SIGN', '-');
        }

        if ($frac_digits == CHAR_MAX) {
            $frac_digits = env('DEFAULT_FRAC_DIGITS', 2);
        }

        if ($p_cs_precedes == CHAR_MAX) {
            $p_cs_precedes = env('DEFAULT_P_CS_PRECEDES', true);
        }

        if ($p_sep_by_space == CHAR_MAX) {
            $p_sep_by_space = env('DEFAULT_P_SEP_BY_SPACE', false);
        }

        if ($n_cs_precedes == CHAR_MAX) {
            $n_cs_precedes = env('DEFAULT_N_CS_PRECEDES', true);
        }

        if ($n_sep_by_space == CHAR_MAX) {
            $n_sep_by_space = env('DEFAULT_N_SEP_BY_SPACE', false);
        }

        if ($p_sign_posn == CHAR_MAX) {
            $p_sign_posn = env('DEFAULT_P_SIGN_POSN', 3);
        }

        if ($n_sign_posn == CHAR_MAX) {
            $n_sign_posn = env('DEFAULT_N_SIGN_POSN', 3);
        }

        // check $NumDigitsAfterDecimal
        if ($NumDigitsAfterDecimal > -1) {
            $frac_digits = $NumDigitsAfterDecimal;
        }

        // check $UseParensForNegativeNumbers
        if ($UseParensForNegativeNumbers == -1) {
            $n_sign_posn = 0;
            if ($p_sign_posn == 0) {
                if (env('DEFAULT_P_SIGN_POSN',3) != 0) {
                    $p_sign_posn = env('DEFAULT_P_SIGN_POSN',3);
                } else {
                    $p_sign_posn = 3;
                }

            }
        } elseif ($UseParensForNegativeNumbers == 0) {
            if ($n_sign_posn == 0) {
                if (env('DEFAULT_P_SIGN_POSN',3) != 0) {
                    $n_sign_posn = env('DEFAULT_P_SIGN_POSN',3);
                } else {
                    $n_sign_posn = 3;
                }
            }

        }

        // check $GroupDigits
        if ($GroupDigits == -1) {
            $mon_thousands_sep = env('DEFAULT_MON_THOUSANDS_SEP','.');
        } elseif ($GroupDigits == 0) {
            $mon_thousands_sep = "";
        }

        // Start by formatting the unsigned number
        $number = number_format(abs($amount) * 100, $frac_digits, $mon_decimal_point, $mon_thousands_sep);

        // check $IncludeLeadingDigit
        if ($IncludeLeadingDigit == 0) {
            if (substr($number, 0, 2) == "0.") {
                $number = substr($number, 1, strlen($number) - 1);
            }

        }

        if ($amount < 0) {
            $sign = $negative_sign;
            $key = $n_sign_posn;
        } else {
            $sign = $positive_sign;
            $key = $p_sign_posn;
        }

        $formats = array('0' => '(%s%%)', '1' => $sign . '%s%%', '2' => $sign . '%s%%', '3' => $sign . '%s%%', '4' => $sign . '%s%%');

        // We then lookup the key in the above array.
        return sprintf($formats[$key], $number);
    }

    /**
     * Remove a formataco do numero string ex: R$ 1.234,56 para 1234.56 ou 33,43% para 33.43
     * @param int $strNumero
     * @return double Numerico
     */
    public static function removerFormatacaoNumero($strNumero)
    {

        $strNumero = trim(str_replace("R$", '', $strNumero));
        $strNumero = trim(str_replace("%", '', $strNumero));

        $vetVirgula = explode(",", $strNumero);
        if (count($vetVirgula) == 1) {
            $acentos = array(".");
            $resultado = str_replace($acentos, "", $strNumero);
            return $resultado;
        } else if (count($vetVirgula) != 2) {
            return $strNumero;
        }

        $strNumero = $vetVirgula[0];
        $strDecimal = mb_substr($vetVirgula[1], 0, 2);

        $acentos = array(".");
        $resultado = str_replace($acentos, "", $strNumero);
        $resultado = $resultado . "." . $strDecimal;

        return $resultado;
    }

    /**
     * Retorna o valor por extenso
     *
     * @param double $valor
     * @param bool $complemento
     * @return string Valor por extenso
     */
    public function valorPorExtenso($valor = 0, $complemento = true)
    {
        $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
        $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");

        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "tr�s", "quatro", "cinco", "seis", "sete", "oito", "nove");

        $z = 0;

        $valor = number_format($valor, 2, ".", ".");
        $inteiro = explode(".", $valor);
        for ($i = 0; $i < count($inteiro); $i++) {
            for ($ii = strlen($inteiro[$i]); $ii < 3; $ii++) {
                $inteiro[$i] = "0" . $inteiro[$i];
            }
        }

        // $fim identifica onde que deve se dar jun��o de centenas por "e" ou por "," ;)
        $fim = count($inteiro) - ($inteiro[count($inteiro) - 1] > 0 ? 1 : 2);
        for ($i = 0; $i < count($inteiro); $i++) {
            $rt = '';
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
            $t = count($inteiro) - 1 - $i;
            if ($complemento == true) {
                $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
                if ($valor == "000") {
                    $z++;
                } elseif ($z > 0) {
                    $z--;
                }

                if (($t == 1) && ($z > 0) && ($inteiro[0] > 0)) {
                    $r .= (($z > 1) ? " de " : "") . $plural[$t];
                }

            }
            if ($r) {
                $rt .= ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? (($i < $fim) ? ", " : " e ") : " ") . $r;
            }
        }

        return ($rt ? $rt : "zero");
    }

    /**
     * Gera uma chave unica UUID - Universal Unique IDentify
     * Metodo de instancia
     */
    public function getUUID()
    {
        return TObject::get_uuid();
    }
    /**
     * Gera uma chave unica UUID - Universal Unique IDentify
     * Metodo de Classe -  Verdadeiro metodo que gera o UUID
     */
    public static function get_uuid()
    {
        $chave = uniqid(rand(), true);
        $parte1 = substr($chave, 0, 8);
        $parte2 = substr($chave, 8, 4);
        $parte3 = substr($chave, 12, 4);
        list($parte4, $parte5) = explode(".", $chave);
        $parte5 .= substr($parte4, rand(4, 18), 4);
        $parte4 = substr($parte4, rand(1, 15), 4);
        return $parte1 . "-" . $parte2 . "-" . $parte3 . "-" . $parte4 . "-" . $parte5;
    }
    /**
     * Converte os caracteres para o padrao ISO-8859-1
     *
     * @param string $text
     */
    public function asciiConvert($text)
    {

        $text = str_replace("�?º�?", "�", $text);
        $text = str_replace(chr(0xC3) . chr(0xBD), "&#226;", $text);
        $text = str_replace("ý", "&#226;", $text);
        $text = str_replace("��", " ", $text);
        $text = str_replace("��", "", $text);
        $text = str_replace("î", "�", $text);
        $text = str_replace("µ", "�", $text);
        $text = str_replace("ã", "�", $text);
        $text = str_replace("á", "�", $text);
        $text = str_replace("â", "�", $text);
        $text = str_replace("��", "�", $text);
        $text = str_replace("é", "�", $text);
        $text = str_replace("ê", "�", $text);
        $text = str_replace("ô", "�", $text);
        $text = str_replace("ó", "�", $text);
        $text = str_replace("õ", "�", $text);
        $text = str_replace("ú", "�", $text);
        $text = str_replace("í", "�", $text);
        $text = str_replace("ç", "�", $text);
        $text = str_replace("º", "�", $text);
        $text = str_replace(chr(0xA2), "�", $text);
        $text = str_replace(chr(0x93), "�", $text);
        $text = str_replace(chr(0x94), "�", $text);
        $text = str_replace(chr(0x82), "�", $text);
        $text = str_replace(chr(0x83), "�", $text);
        $text = str_replace(chr(0x85), "�", $text);
        $text = str_replace(chr(0x88), "�", $text);
        $text = str_replace(chr(0x90), "�", $text);
        $text = str_replace(chr(0x87), "�", $text);
        $text = str_replace(chr(0x8C), "�", $text);
        $text = str_replace(chr(0xA0), "�", $text);
        $text = str_replace(chr(0xA1), "�", $text);
        $text = str_replace(chr(0xA2), "�", $text);
        $text = str_replace(chr(0xA3), "�", $text);
        $text = str_replace(chr(0xE4), "�", $text);
        $text = str_replace(chr(0xC3), "�", $text);
        $text = str_replace(chr(0xC6), "�", $text);
        $text = str_replace(chr(0xC7), "�", $text);
        $text = str_replace(chr(0xB5), "�", $text);
        $text = str_replace(chr(0xD3), "�", $text);
        $text = str_replace(chr(0xD4), "�", $text);
        $text = str_replace(chr(0xD5), "�", $text);
        $text = str_replace(chr(0xD6), "�", $text);
        $text = str_replace(chr(0xA7), "�", $text);
        $text = str_replace(chr(0xA6), "�", $text);
        $text = str_replace(chr(0xC3) . chr(0xFF), "�", $text);

        return $text;
    }

    // FIM asciiConvert

    /**
     *
     * @param string $url Endereco url
     * @param array $post_data Array com nome do campo e valor do parametos passados por POST
     * @return string Resposta da url
     */
    public function http($url, $post_data = null)
    {

        $ch = curl_init();
        if (defined("CURL_CA_BUNDLE_PATH")) {
            curl_setopt($ch, CURLOPT_CAINFO, env('CURL_CA_BUNDLE_PATH',''));
        }

        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.6 (KHTML, like Gecko) Chrome/16.0.897.0 Safari/535.6');
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        if (isset($post_data)) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }
        $response = curl_exec($ch);

        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $lastAPICall = $url;
        $curl_Errono = curl_errno($ch);
        $curl_Error = curl_error($ch);
        curl_close($ch);

        return $response;
    }

    /**
     *
     * @param boolean $onlypath Caso verdadeiro mostra apenas o caminho caso exista ate o ultimo /
     * @return string
     */
    public function curPageURL($onlypath = false)
    {
        $pageURL = 'http';
        if ($_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        } // Verifica se e ssl
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") { // Verifica se esta em outra porte diferente da 80  e adiciona
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        if ($onlypath) {
            $pageURL = substr($pageURL, 0, strrpos($pageURL, '/') + 1);
        }
        return $pageURL;
    }

    /**
     *  Exibe um erro tratado com try
     *
     * @param Exception $e
     * @return bool
     */
    public function exceptionDisplay($e)
    {
        echo str_repeat("<br>", 3) . "<hr />";
        echo "Exception code:  <font style='color:blue'>" . $e->getCode() . "</font>";
        echo "<br />";
        echo "Exception message: <font style='color:blue'>" . nl2br($e->getMessage()) . "</font>";
        echo "<br />";
        echo "Thrown by: '" . $e->getFile() . "'";
        echo "<br />";
        echo "on line: '" . $e->getLine() . "'.";
        echo "<br />";
        echo "<br />";
        echo "Stack trace:";
        echo "<br />";
        echo nl2br($e->getTraceAsString());
        echo "<hr />" . str_repeat("<br>", 3);
        return true;
    }

    //FIM exceptionDisplay

    /**
     * Remove formatacao dos campos -./
     * @param string $valor
     * @return string
     */
    public function __removeFormat($valor = '')
    {
        $valor = preg_replace("(/[' '-./ t]/)", '', $valor);
        $valor = str_replace('.', '', $valor);
        $valor = str_replace('-', '', $valor);
        $valor = str_replace('/', '', $valor);
        $valor = str_replace(' ', '', $valor);
        return $valor;
    }

    /**
     * __format
     * Fun��o de formata��o de strings.
     * Imcorporado em 12/06/2012
     * @package NFePHP
     * @name __format
     * @version 1.0
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param string $campo String a ser formatada
     * @param string $mascara Regra de formata��o da string (ex. ##.###.###/####-##)
     * @return string Retorna o campo formatado
     */
    public function __format($campo = '', $mascara = '')
    {
        //remove qualquer formata��o que ainda exista
        $sLimpo = preg_replace("(/[' '-./ t]/)", '', $campo);
        // pega o tamanho da string e da mascara
        $tCampo = strlen($sLimpo);
        $tMask = strlen($mascara);
        if ($tCampo > $tMask) {
            $tMaior = $tCampo;
        } else {
            $tMaior = $tMask;
        }
        //contar o numero de cerquilhas da mascara
        $aMask = str_split($mascara);
        $z = 0;
        $flag = false;
        foreach ($aMask as $letra) {
            if ($letra == '#') {
                $z++;
            }
        }
        if ($z > $tCampo) {
            //o campo � menor que esperado
            $flag = true;
        }
        //cria uma vari�vel grande o suficiente para conter os dados
        $sRetorno = '';
        $sRetorno = str_pad($sRetorno, $tCampo + $tMask, " ", STR_PAD_LEFT);
        //pega o tamanho da string de retorno
        $tRetorno = strlen($sRetorno);
        //se houve entrada de dados
        if ($sLimpo != '' && $mascara != '') {
            //inicia com a posi��o do ultimo digito da mascara
            $x = $tMask;
            $y = $tCampo;
            $cI = 0;
            for ($i = $tMaior - 1; $i >= 0; $i--) {
                if ($cI < $z) {
                    // e o digito da mascara � # trocar pelo digito do campo
                    // se o inicio da string da mascara for atingido antes de terminar
                    // o campo considerar #
                    if ($x > 0) {
                        $digMask = $mascara[--$x];
                    } else {
                        $digMask = '#';
                    }
                    //se o fim do campo for atingido antes do fim da mascara
                    //verificar se � ( se n�o for n�o use
                    if ($digMask == '#') {
                        $cI++;
                        if ($y > 0) {
                            $sRetorno[--$tRetorno] = $sLimpo[--$y];
                        } else {
                            //$sRetorno[--$tRetorno] = '';
                        }
                    } else {
                        if ($y > 0) {
                            $sRetorno[--$tRetorno] = $mascara[$x];
                        } else {
                            if ($mascara[$x] == '(') {
                                $sRetorno[--$tRetorno] = $mascara[$x];
                            }
                        }
                        $i++;
                    }
                }
            }
            if (!$flag) {
                if ($mascara[0] != '#') {
                    $sRetorno = '(' . trim($sRetorno);
                }
            }
            return trim($sRetorno);
        } else {
            return '';
        }
    }

    //fim __format

    /**
     * __simpleGetValue
     * Extrai o valor do node DOM
     * Imcorporado em 12/06/2012
     * @package NFePHP
     * @version 1.01
     * @author Marcos Diez
     * @param DOM $theObj
     * @param string $keyName identificador da TAG do xml
     * @param string $extraBefore Inserido antes do retorno
     * @param string $extraTextAfter Inserido depois do retorno
     * @return string
     */
    public function __simpleGetValue($theObj, $keyName, $extraBefore = "", $extraTextAfter = "")
    {
        $vct = $theObj->getElementsByTagName($keyName)->item(0);
        if (isset($vct)) {
            return $extraBefore . trim($vct->nodeValue) . $extraTextAfter;
        }
        return "";
    }

    //fim __simpleGetValue

    /**
     * __simpleGetDate
     * Recupera e reformata a data do padrão da NFe para dd/mm/aaaa
     * Imcorporado em 18/06/2012
     * @package NFePHP
     * @version 1.0
     * @author Marcos Diez
     * @param DOM $theObj
     * @param string $keyName identificador da TAG do xml
     * @param string $extraText prefixo do retorno
     * @return string
     */
    public function __simpleGetDate($theObj, $keyName, $extraText)
    {
        $vct = $theObj->getElementsByTagName($keyName)->item(0);
        if (isset($vct)) {
            $theDate = explode("-", $vct->nodeValue);
            return $extraText . $theDate[2] . "/" . $theDate[1] . "/" . $theDate[0];
        }
        return "";
    }

    //fim __simpleGetDate

    /**
     * Converte os caracteres para UPPER CASE e remove acentos
     *
     */
    public function __removeStrUpper($value = "")
    {
        setlocale(LC_CTYPE, "pt_BR");
        $value = str_replace("�", "C", $value);
        $value = str_replace("&", " ", $value);
        $value = str_replace("�", "A", $value);
        $value = str_replace("�", "A", $value);
        $value = str_replace("�", "A", $value);
        $value = str_replace("�", "A", $value);
        $value = str_replace("�", "E", $value);
        $value = str_replace("�", "E", $value);
        $value = str_replace("�", "E", $value);
        $value = str_replace("�", "I", $value);
        $value = str_replace("�", "I", $value);
        $value = str_replace("�", "I", $value);
        $value = str_replace("�", "O", $value);
        $value = str_replace("�", "O", $value);
        $value = str_replace("�", "O", $value);
        $value = str_replace("�", "O", $value);
        $value = str_replace("�", "U", $value);
        $value = str_replace("�", "U", $value);
        $value = str_replace("�", "U", $value);
        $value = str_replace("�", "C", $value);
        $value = str_replace("�", "A", $value);
        $value = str_replace("�", "A", $value);
        $value = str_replace("�", "A", $value);
        $value = str_replace("�", "A", $value);
        $value = str_replace("�", "E", $value);
        $value = str_replace("�", "E", $value);
        $value = str_replace("�", "E", $value);
        $value = str_replace("�", "I", $value);
        $value = str_replace("�", "I", $value);
        $value = str_replace("�", "I", $value);
        $value = str_replace("�", "O", $value);
        $value = str_replace("�", "O", $value);
        $value = str_replace("�", "O", $value);
        $value = str_replace("�", "O", $value);
        $value = str_replace("�", "U", $value);
        $value = str_replace("�", "U", $value);
        $value = str_replace("�", "U", $value);
        $value = strtoupper($value);

        return $value;
    }

    /**
     * __convertTime
     * Converte a imformacao de data e tempo contida na NFe
     * @package NFePHP
     * @name __convertTime
     * @version 1.0
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param string $DH Informa��o de data e tempo extraida da NFe
     * @return timestamp UNIX Para uso com a fun�ao date do php
     */
    public function __convertTime($DH)
    {
        if ($DH) {
            $aDH = explode('T', $DH);
            $adDH = explode('-', $aDH[0]);
            $atDH = explode(':', $aDH[1]);
            $timestampDH = mktime($atDH[0], $atDH[1], $atDH[2], $adDH[1], $adDH[2], $adDH[0]);
            return $timestampDH;
        }
    }

    //fim da função __convertTime

    /**
     * Converte segundos para formado de hora HH:MM:SS
     *
     * @param $seconds
     * @return Time
     */
    public static function __seconds2Hours($seconds)
    {
        $hours = floor($seconds / 3600);
        $seconds -= $hours * 3600;
        $minutes = floor($seconds / 60);
        $seconds -= $minutes * 60;
        $seconds = round($seconds);
        if (strlen($hours) == 1) {
            $hours = "0" . $hours;
        }
        if (strlen($minutes) == 1) {
            $minutes = "0" . $minutes;
        }
        if (strlen($seconds) == 1) {
            $seconds = "0" . $seconds;
        }
        return $hours . ":" . $minutes . ":" . $seconds;
    }

    /**
     * __validaCNPJ
     * Valida o numero de CNPJ
     *
     * @version 1.0
     * @param string $cnpj Numero do cnpj a verificar
     * @return bool retorna verdadeiro se o cnpj for valido
     */
    public static function __validaCNPJ($cnpj)
    {
        $cnpj = preg_replace("@[./-]@", "", $cnpj);
        if (strlen($cnpj) != 14 or !is_numeric($cnpj)) {
            return 0;
        }
        $j = 5;
        $k = 6;
        $soma1 = "";
        $soma2 = "";

        for ($i = 0; $i < 13; $i++) {
            $j = $j == 1 ? 9 : $j;
            $k = $k == 1 ? 9 : $k;
            $soma2 += (intval($cnpj[$i]) * $k);

            if ($i < 12) {
                $soma1 += ($cnpj[$i] * $j);
            }
            $k--;
            $j--;
        }

        $digito1 = $soma1 % 11 < 2 ? 0 : 11 - $soma1 % 11;
        $digito2 = $soma2 % 11 < 2 ? 0 : 11 - $soma2 % 11;
        return (($cnpj[12] == $digito1) and ($cnpj[13] == $digito2));
    }

    /**
     * __validaCPF
     * Valida o numero de CPF
     * @param string $cpf
     * @return boolean
     */
    public static function __validaCPF($cpf)
    {
        // Extrai somente os números
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        if (preg_match('/(\d)\1{10}/', $cpf)) {

            return false;
        }

        // Verifica se nenhuma das sequencias abaixo foi digitada, caso seja, retorna falso
        if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999') {

            return false;
        } else {

            // Faz o calculo para validar o CPF
            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf[$c] * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf[$c] != $d) {
                    return false;
                }
            }
            return true;
        }
    }

    /**
     * __validaEmail
     * Valida se email esta digitado corretamente e verifica tambem o dominio correto
     * @param string $email
     * @return boolean
     */
    public static function __validaEmail($email)
    {
        //verifica se e-mail esta no formato correto de escrita
        if (strpos($email, '@') <= 0) {
            return false;
        } else {
            //Valida o dominio
            $dominio = explode('@', $email);
            if (!checkdnsrr($dominio[1], 'A')) {
                return false;
            } else {
                return filter_var($email, FILTER_VALIDATE_EMAIL);
            }
        }
    }

    /**
     * __ymd2dmy
     * Converte datas no formato YMD (ex. 2009-11-02) para o formato brasileiro 02/11/2009)
     * @package NFePHP
     * @name __ymd2dmy
     * @version 1.0
     * @author Roberto L. Machado <linux.rlm at gmail dot com>
     * @param string $data Par�metro extraido da NFe
     * @return string Formatada para apresenta��o da data no padr�o brasileiro
     */
    public function __ymd2dmy($data)
    {
        if (!empty($data)) {
            $needle = "/";
            if (strstr($data, "-")) {
                $needle = "-";
            }
            $dt = explode($needle, $data);
            return "$dt[2]/$dt[1]/$dt[0]";
        }
    }

    // fim da funcao __ymd2dmy

    /**
     * __dmyhms2timestamp
     * Converte datas no formato DD/MM/AAAATHH:MM:SS ou DD/MM/AAAA HH:MM:SS (ex. 02/11/2009T14:34:33 ou 02/11/2009 14:34:33) para um numero timestamp
     * @since 27/09/2012
     * @package LXGestor
     * @name __dmyhms2timestamp
     * @version 1.0
     * @author Nataniel Fiuza <natan.fiuza at gmail dot com>
     * @param datetime $data
     * @return timestamp
     */
    public function __dmyhms2timestamp($data = '')
    {
        if (!empty($data)) {
            $needle = "/";

            if (strstr($data, 'T')) {
                list($dt, $hr) = explode("T", $data);
            } elseif (strstr($data, ' ')) {
                list($dt, $hr) = explode(" ", $data);
            }
            $dt = explode($needle, $dt);
            if (strstr($hr, ":")) {
                $needle = ":";
            }
            $hr = explode($needle, $hr);
            return mktime($hr[0], $hr[1], $hr[2], $dt[1], $dt[0], $dt[2]);
        }
    }

    /**
     * __datetime2timestamp
     * Converte datas no formato AAAA-MM-DDTHH:MM:SS ou AAAA-MM-DD HH:MM:SS (ex. 2009-11-02T14:34:33 ou 2009-11-02 14:34:33) para um numero timestamp
     * @since 27/09/2012
     * @package LXGestor
     * @name __datetime2timestamp
     * @version 1.0
     * @author Nataniel Fiuza <natan.fiuza at gmail dot com>
     * @param datetime $data
     * @return timestamp
     */
    public static function __datetime2timestamp($data = '')
    {
        if (!empty($data)) {
            $needle = "-";

            if (strstr($data, 'T')) {
                list($dt, $hr) = explode("T", $data);
            } elseif (strstr($data, ' ')) {
                list($dt, $hr) = explode(" ", $data);
            }
            $dt = explode($needle, $dt);
            if (strstr($hr, ":")) {
                $needle = ":";
            }
            $hr = explode($needle, $hr);
            return mktime($hr[0], $hr[1], $hr[2], $dt[1], $dt[2], $dt[0]);
        }
    }

    /**
     * Converte datas no formato oficial do W3c para formato datetime mysql
     * Formato: YYYY-MM-DDThh:mm:ss.sTZD, o formato oficial do W3C para datas.
     *
     *
     * @since 27/03/2013
     * @package LXGestor
     * @name __w3cdate2datetime
     * @version 1.0
     * @author Nataniel Fiuza <natan.fiuza at gmail dot com>
     * @param W3CDate $data
     * @return timestamp
     */
    public static function __w3cdate2datetime(string $data = '')
    {
        if (!empty($data)) {
            $needle = "T";
            $needle1 = ".";
            $needle2 = ":";
            $needle3 = "-";
            if (strstr($data, $needle)) {
                list($dt, $thr) = explode($needle, $data);
            }
            if (strstr($dt, $needle3)) {
                $dt = explode($needle3, $dt);
            }
            if (strstr($thr, ".")) {
                $hr = explode($needle1, $thr);
            }
            $hr = explode($needle2, $hr[0]);
            return $dt[0] . "-" . $dt[1] . "-" . $dt[2] . " " . $hr[0] . ":" . $hr[1] . ":" . $hr[2];
        }
    }

    /**
     * __datetime2dmyhms
     * Converte datas no formato AAAA-MM-DDTHH:MM:SS (ex. 2009-11-02T14:34:33) para o formato brasileiro 02/11/2009 14:34:33)
     * @package LXGestor
     * @name __datetime2dmyhms
     * @version 1.0
     * @author Nataniel Fiuza <natan.fiuza at gmail dot com>
     * @param string $data Parametro extraido da NFe
     * @param string $retorno Formato do retorno desejado padrao d/m/Y
     * @return string Formatada para apresentacao da data no padrao brasileiro
     */
    public static function __datetime2dmyhms($data, $retorno = 'd/m/Y H:i:s')
    {
        if (!empty($data)) {
            $needle = "/";
            if (strstr($data, "-")) {
                $needle = "-";
            }
            if (strstr($data, 'T')) {
                list($dt, $hr) = explode("T", $data);
            } elseif (strstr($data, ' ')) {
                list($dt, $hr) = explode(" ", $data);
            } else {
                $dt = $data;
                $hr = "00:00:00";
            }
            $dt = explode($needle, $dt);
            if (strstr($data, "-")) {
                $retorno = str_replace('d', $dt[2], $retorno);
                $retorno = str_replace('m', $dt[1], $retorno);
                $retorno = str_replace('Y', $dt[0], $retorno);
            } else {
                $retorno = str_replace('d', $dt[0], $retorno);
                $retorno = str_replace('m', $dt[1], $retorno);
                $retorno = str_replace('Y', $dt[2], $retorno);
            }
            if (strstr($hr, ":")) {
                $needle = ":";
            }
            $hr = explode($needle, $hr);
            $hr[2] = substr($hr[2], 0, 2);
            $retorno = str_replace('H', $hr[0], $retorno);
            $retorno = str_replace('i', $hr[1], $retorno);
            $retorno = str_replace('s', $hr[2], $retorno);

            return $retorno;
        }
    }

    // fim da funcao __datetime2dmyhms

    /**
     * __datetime2dmyhms
     * Converte datas no formato DD/MM/AAAATHH:MM:SS (ex. 25/09/2012T14:34:33 ou 25/09/2012 14:34:33) para o formato mysql 2012-09-25 14:34:33)
     * @package LXGestor
     * @name __datetime2dmyhms
     * @version 1.0
     * @author Nataniel Fiuza <natan.fiuza at gmail dot com>
     * @param string $data Par�metro extraido da NFe
     * @param string $retorno Formato do retorno desejado padrao d/m/Y
     * @return string Formatada para apresenta��o da data no padr�o brasileiro
     */
    public function __dmyhms2datetime($data, $retorno = 'Y-m-d H:i:s')
    {
        if (!empty($data)) {
            $needle = "/";
            if (strstr($data, "-")) {
                $needle = "-";
            }
            if (strstr($data, 'T')) {
                list($dt, $hr) = explode("T", $data);
            } elseif (strstr($data, ' ')) {
                list($dt, $hr) = explode(" ", $data);
            } else {
                $dt = $data;
                $hr = "00:00:00";
            }
            $dt = explode($needle, $dt);
            $retorno = str_replace('d', $dt[0], $retorno);
            $retorno = str_replace('m', $dt[1], $retorno);
            $retorno = str_replace('Y', $dt[2], $retorno);
            if (strstr($hr, ":")) {
                $needle = ":";
            }
            $hr = explode($needle, $hr);
            $retorno = str_replace('H', $hr[0], $retorno);
            $retorno = str_replace('i', $hr[1], $retorno);
            $retorno = str_replace('s', $hr[2], $retorno);

            return $retorno;
        }
    }

// fim da fun��o __datetime2dmyhms

    /**
     * __limpaString
     * Remove todos dos caracteres especiais do texto e os acentos
     * preservando apenas letras de A-Z numeros de 0-9 e os caracteres @ , - ; : / _
     *
     * @version 1.0.3
     * @package NFePHP
     * @author  Roberto L. Machado <linux.rlm at gmail dot com>
     * @return  string Texto sem caractere especiais
     */
    public function __limpaString($texto)
    {
        $aFind = array('&', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�');
        $aSubs = array('e', 'a', 'a', 'a', 'a', 'e', 'e', 'i', 'o', 'o', 'o', 'u', 'u', 'c', 'A', 'A', 'A', 'A', 'E', 'E', 'I', 'O', 'O', 'O', 'U', 'U', 'C');
        $novoTexto = str_replace($aFind, $aSubs, $texto);
        $novoTexto = preg_replace("/[^a-zA-Z0-9 @,-.;:\/_]/", "", $novoTexto);
        return $novoTexto;
    }

//fim __limpaString

    /**
     * validEmailAdd
     * Fun��o de valida��o dos endere�os de email
     *
     * @package NFePHP
     * @name validEmailAdd
     * @version 1.02
     * @author  Douglas Lovell <http://www.linuxjournal.com/article/9585>
     * @param string $email Endere�o de email a ser testado, podem ser passados v�rios endere�os separados por virgula
     * @return boolean True se endere�o � verdadeiro ou false caso haja algum erro
     */
    public function validEmailAdd($email)
    {
        $isValid = true;
        $aMails = explode(',', $email);
        foreach ($aMails as $email) {
            $atIndex = strrpos($email, "@");
            if (is_bool($atIndex) && !$atIndex) {
                $this->mailERROR .= "$email - Isso n�o � um endere�o de email.\n";
                $isValid = false;
            } else {
                $domain = substr($email, $atIndex + 1);
                $local = substr($email, 0, $atIndex);
                $localLen = strlen($local);
                $domainLen = strlen($domain);
                if ($localLen < 1 || $localLen > 64) {
                    // o endere�o local � muito longo
                    $this->mailERROR .= "$email - O endereço é muito longo.\n";
                    $isValid = false;
                } else if ($domainLen < 1 || $domainLen > 255) {
                    // o comprimento da parte do dominio � muito longa
                    $this->mailERROR .= "$email - O comprimento do dominio é muito longo.\n";
                    $isValid = false;
                } else if ($local[0] == '.' || $local[$localLen - 1] == '.') {
                    // endere�o local inicia ou termina com ponto
                    $this->mailERROR .= "$email - Parte do endere�o inicia ou termina com ponto.\n";
                    $isValid = false;
                } else if (preg_match('/\\.\\./', $local)) {
                    // endere�o local com dois pontos consecutivos
                    $this->mailERROR .= "$email - Parte do endere�o tem dois pontos consecutivos.\n";
                    $isValid = false;
                } else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
                    // caracter n�o valido na parte do dominio
                    $this->mailERROR .= "$email - Caracter n�o v�lido na parte do dom�nio.\n";
                    $isValid = false;
                } else if (preg_match('/\\.\\./', $domain)) {
                    // parte do dominio tem dois pontos consecutivos
                    $this->mailERROR .= "$email - Parte do dom�nio tem dois pontos consecutivos.\n";
                    $isValid = false;
                } else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\", "", $local))) {
                    // caracter n�o valido na parte do endere�o
                    if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\", "", $local))) {
                        $this->mailERROR .= "$email - Caracter n�o v�lido na parte do endere�o.\n";
                        $isValid = false;
                    }
                }
                if ($isValid && !(checkdnsrr($domain, "MX") || checkdnsrr($domain, "A"))) {
                    // dominio n�o encontrado no DNS
                    $this->mailERROR .= "$email - O dom�nio n�o foi encontrado no DNS.\n";
                    $isValid = false;
                }
            }
        }
        return $isValid;
    }

    //fim função validEmailAdd

    /**
     * Recebe um query string de busca padrao google e retorna em SQL usando o campo field
     *
     * @param string $q
     * @param string $field
     */
    public function querySearchParser($q, $field)
    {
        $q = str_replace('  ', ' ', $q);
        $q = str_replace('+', ' ', $q);
        $chars = str_split(trim($q));

        $bloco = 0;
        $strbloco = '';
        $palavras = array();
        foreach ($chars as $char) {
            if (($char == "\"") && ($bloco == 0)) {
                $bloco = 1;
            } elseif (($char == "\"") && ($bloco == 1)) {
                $bloco = 0;
                $palavras[] = $strbloco;
                $strbloco = '';
            } elseif (($char == " ") && ($bloco == 1)) {
                $strbloco .= $char;
            } elseif (($char == " ") && ($bloco == 0)) {
                if (!empty($strbloco)) {
                    $palavras[] = $strbloco;
                    $strbloco = '';
                }
            } elseif ($char == "+") {
                $palavras[] = '+';
            } else {
                $strbloco .= $char;
            }
        }
        if (!empty($strbloco)) {
            $palavras[] = $strbloco;
        }

        $query = '';
        foreach ($palavras as $value) {
            $query .= " $field like " . TQuery::getSQLValueStringLike($value) . ' or';
        }
        if (substr($query, strlen($query) - 2, strlen($query)) == "or") {
            $query = substr($query, 0, strlen($query) - 2);
        }
        return $query;
    }
    /**
     * Converte um array para uma string xml
     * @param array $array Array a ser convertida
     * @param string $rootElement Root element do XML padrão `<root/>`
     * @return string XML string
     */
    public function array_to_xml($array, $rootElement = null, $xml = null)
    {
        $_xml = $xml;

        if (!is_array($array)) {
            return '';
        }

        // If there is no Root Element then insert root
        if ($_xml === null) {
            try {
                $_xml = new SimpleXMLElement($rootElement !== null ? $rootElement : '<root/>');
            } catch (\Exception $e) {
                print_r($e);
            }
        }

        // Visit all key value pair
        foreach ($array as $k => $v) {

            // If there is nested array then
            if (is_array($v)) {

                // Call function for nested array
                $this->array_to_xml($v, $k, $_xml->addChild($k == null ? 'null' : $k));
            } else {

                // Simply add child element.
                try {

                    $_xml->addChild($k == null ? 'indefined' : $k, $v == null ? [] : $v);
                } catch (\Exception $e) {
                    print_r($e);
                }
            }
        }

        return $_xml->asXML();
    }
}

// FIM class TObject
