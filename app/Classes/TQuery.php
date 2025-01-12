<?php

namespace App\Classes;

use App\Classes\TObject;
use Illuminate\Support\Facades\DB;
use PDO;
use PDOException;

/**
 * Controle todos os acessos ao banco de dados
 *
 * 27/09/2024 v 2.0 - Versao para o Laravel
 * 25/07/2008 v 1.1 -  Erro de "Duplicate entry" nao e exibido na funcao execute retorna false. Adicionada a propriedade "sql_error" string contendo o ultimo erro encontrado
 *
 * @since 10/07/2006
 * @author    Nataniel Fiuza <natan.fiuza@gmail.com>
 * @version 2.0
 * @package    database
 * @copyright NatanFiuza.dev.br
 *
 */
class TQuery extends TObject
{

    /**
     * Determina qual a conexao usa a padrao se nao informar
     *
     *
     */
    public $connection;

    /**
     * Recebe os comandos SQL
     *
     * @var string
     */
    public $sql = null;

    /**
     * Recebe os campos do fetch array ou object
     *
     * @var unknown_type
     */
    public $fields = array();
    /**
     * Recebe todos os registros gerados por uma consulta
     *
     * @var array
     */
    public $rows = array();

    /**
     * Numero de registros que foram afetados com o ultimo comando executado
     *
     * @var integer
     */
    public $affected_rows = 0;

    /**
     * Recebe o ultimo erro de sql encontrado
     *
     * @var string
     */
    public $sql_error = '';

    public $time_execute = 0;
    /**
     * Recebe o rretorno do DB::select()
     *
     * @var handle
     */
    private $socket;

    private $execute_last_file = null;
    private $execute_last_line = null;
    /**
     * Tempo minimo para registrar uma query lenta
     *
     * @var integer
     */
    private $time_slow_query = 10;
    /**
     * Controle todos os acessos ao banco de dados, execução de querys
     *
     * 27/09/2024 v 2.0 - Versao para o Laravel
     *
     * 25/07/2008 v 1.1 - Erro de "Duplicate entry" nao e exibido na funcao execute retorna false. Adicionada a propriedade "sql_error" string contendo o ultimo erro encontrado
     *
     * @since 2006-07-10
     * @author    Nataniel Fiuza <natan.fiuza@gmail.com>
     * @copyright NatanFiuza.dev.br
     * @param string `$connection`
     */
    public function __construct($connection = '')
    {
        $this->connection = $connection;

    }

    /**
     *  Adiciona uma string para a sql
     *  Usar antes o metodo Clear caso nao queira adicionar mais uma query a sql
     * @param string `$pQuery` Recebe uma string SQL
     * @return this object
     */
    public function add($pQuery = '')
    {
        $this->sql .= " " . $pQuery;
        return $this;
    }

    /**
     * Limpa todos os resultados anteriores existentes
     *
     * @access public
     */
    public function clear()
    {
        $this->sql = "";
        $this->sql_error = "";
        $this->fields = array();
        $this->rows = array();
        $this->affected_rows = 0;
        return $this;
    }

    /**
     * Executa a query
     *
     * @throws `PDOException` Retorna se nao executar a query corretamente
     * @param string `$file` Recebe a constante __FILE__ identificando o arquivo onde foi chamado o metodo
     * @param string `$line` Recebe a constante __LINE__ identificando a linha arquivo onde foi chamado o metodo
     * @param boolean $error_report Verdadeiro gera um `throws` e salva o arquivo com o log do erro gerado. Falso retorna o estado da excução como `boolean`. Padrão `true`     *
     * @return boolean
     */
    public function execute($file = '', $line = '', $error_report = true)
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 0);
        $file == '' ? $file = $trace[0]['file'] ?? 'Unknown' : null;
        $line == '' ? $line = $trace[0]['line'] ?? 'Unknown' : null;
        $this->execute_last_file = $file;
        $this->execute_last_line = $line;

        try {

            $inicio = microtime(true);

            $this->sql = " -- IDQUERY: LARAVEL -  File: $file | Line: $line | Connection: " . $this->connection . " | Date: [" . date('Y-m-d H:i:s') . "]\n" . $this->sql;
            $this->socket = empty(trim($this->connection)) ? DB::select($this->sql) : DB::connection($this->connection)->select($this->sql);
            $this->time_execute = microtime(true) - $inicio;

            $this->time_execute >= $this->time_slow_query ? $this->save_slow_query() : null;

            $this->affected_rows = count(is_array($this->socket) ? $this->socket : []);

        } catch (PDOException $e) {
            $this->sql_error = $e->getMessage();
            if ($error_report) {
                $error = new TMyError("", 5);
                $error->query_sql = $this->sql;
                $error->error_line = $line;
                $error->error_file = $file;
                $error->class_identify = __CLASS__ . "::" . __FUNCTION__;
                $error->exception_message = $e->getMessage();
                $error->class_name = __CLASS__;
                $error->function_name = __FUNCTION__;
                $error->exception_filename = __FILE__;
                $error->save_error_file();
                throw new PDOException('TQuery.execute: ' . $e->getMessage());
            }
            return false;
        }

        return true;
    }
    /**
     * Salva o sql do ultimo `execute` realizado ou $sql informado para um arquivo .log
     *
     * @param string $sql Caso não queira usar o ultimo arquivo sql informado
     * @param string $file File da linha onde foi usada a funcao ou o file passado na funcao execute
     * @param integer $line Line da linha onde foi usada a funcao ou a Line passada na funcao execute
     * @return int|false  Retorna a quantidade de bytes que foi escrita no arquivo ou false em caso de falha.
     */
    public function save_sql_as_file($sql = '', $file = __FILE__, $line = __LINE__)
    {

        $filename = isset($this->execute_last_file) ? $this->execute_last_file : $file;
        $fileline = isset($this->execute_last_line) ? $this->execute_last_line : $line;
        $sql = isset($this->sql) ? $this->sql : $sql;
        return file_put_contents(
            'TQuery_SQL_' .
            str_replace(".php", "", basename($filename)) .
            "_" . $fileline . "_" .
            date('Ymd_His') . '.log',
            "-- Data: " . date('Y-m-d H:i:s') . "\n" .
            "-- File: " . $filename . "\n" .
            "-- Line: " . $fileline . "\n\n" .
            "-- ----SQL----\n" . $sql
        );
    }
    /**
     * Fecha a query e o link da ultima conexao aberta
     *
     * @access public
     */
    public function close()
    {

        $this->sql = '';
        $this->fields = [];
        $this->socket = [];
    }
    /**
     * Fecha o cursor, permitindo que a instrução seja executada novamente
     * @access public
     */
    public function close_cursor()
    {
        $this->socket = [];
        return false;
    }
    /**
     * Retorna o Id da ultima co
     *
     * @access public
     */
    public function lastInsertId()
    {
        return empty($this->connection) ? DB::getPdo()->lastInsertId() : DB::connection($this->connection)->getPdo()->lastInsertId();
    }

    /**
     * Carrega o registro para uma array
     *
     * @access public
     */
    public function fetch_array()
    {
        if (count(is_array($this->socket) ? $this->socket : []) > 0) {
            return json_decode(json_encode($this->socket[0]), true);
        }
        return [];
    }

    /**
     * Carrega o registro e retorna como um objeto
     *
     * @access public
     */
    public function fetch_object()
    {
        if (count(is_array($this->socket) ? $this->socket : []) > 0) {
            return $this->socket[0];

        }
        return [];
    }

    /**
     * Carrega todos os registro para list
     * @param bool `$as_object` Verdadeiro retorna um array de objetos
     * @access public
     * @return array
     */
    public function fetch_all($as_object = false)
    {
        return $this->rows = ($as_object ? $this->socket : json_decode(json_encode($this->socket), true));
    }
    /**
     * Passa o valor para um parametro na query
     * @param string `$pCampo` Nome do campo a ser substituido
     * @param string `$pValor` Valor a ser substituido pelo campo
     * @param string `$pAcao` Determina qual acção tomar com o valor passado.<br> __`normal` (padrão) - Apenas remove sql injectios e retorna o valor entre ''__ <br> __`float` - Converte o valor trocando , por 'ponto'__ <br> __`html` - Onde encontrar ' substitui por \' no mysql ou '' no mssql__
     * @param string `$file` Path do arquivo onde foi executado o metodo
     * @param string `$line` Path do arquivo onde foi executado o metodo
     * @access public
     */
    public function paramByName($pCampo, $pValor, $pAcao = "normal", $file = __FILE__, $line = __LINE__)
    {
        $erro = 0;
        $separador = " ";
        if (strpos($this->sql . " ", ":" . $pCampo . " ") > 0) {
            $separador = " ";
            $erro = 1;
        }
        if (strpos($this->sql . " ", ":" . $pCampo . ",") > 0) {
            $separador = ",";
            $erro = 1;
        }
        if (strpos($this->sql . " ", ":" . $pCampo . ")") > 0) {
            $separador = ")";
            $erro = 1;
        }
        if (strpos($this->sql . " ", ":" . $pCampo . chr(13)) > 0) {
            $separador = chr(13);
            $erro = 1;
        }
        if (strpos($this->sql . " ", ":" . $pCampo . chr(10)) > 0) {
            $separador = chr(10);
            $erro = 1;
        }
        if (strpos($this->sql . " ", ":" . $pCampo . chr(9)) > 0) {
            $separador = chr(9);
            $erro = 1;
        }

        if ($erro == 0) {
            $error = new TMyError("", 0, '', $file, $line);
            throw new \Exception($error->getError(3, $pCampo), 1);
        }

        $this->sql = str_replace(":<dataAtual>", "'" . date("Y-m-d") . "'", $this->sql . " ");
        $this->sql = str_replace(":<DataAtual>", "'" . date("Y-m-d") . "'", $this->sql . " ");

        $this->sql = str_replace(":<horaAtual>", "'" . date("H:i:s") . "'", $this->sql . " ");
        $this->sql = str_replace(":<HoraAtual>", "'" . date("H:i:s") . "'", $this->sql . " ");
        $this->sql = str_replace(":<horaatual>", "'" . date("H:i:s") . "'", $this->sql . " ");
        if ($pAcao == "normal") {
            $this->sql = str_replace(":" . $pCampo . $separador, (is_null($pValor) ? 'NULL' : $this->getSQLValueString($pValor)) . $separador, $this->sql . " ");
        }
        if ($pAcao == "float") {
            $pValor = str_replace(",", chr(46), $pValor);
            $this->sql = str_replace(":" . $pCampo . $separador, $this->getSQLValue($pValor) . $separador, $this->sql . " ");

            $pValor = str_replace(chr(39), chr(92) . chr(39), $pValor);

            $this->sql = str_replace(":" . $pCampo . $separador, "'" . $pValor . "'" . $separador, $this->sql . " ");
        }
    }

    /**
     * Carrega o valor de um campo pelo nome.
     *
     * @param string `$pCampo` Retorna o valor do campo pelo seu nome de chave
     * @access public
     */
    public function fieldByName($pCampo)
    {
        if (isset($this->fields[$pCampo])) {
            return $this->fields[$pCampo];
        } else {
            return "";
        }
    }

    /**
     * Retorna a quantidade de registros encontrados
     * @access public
     * @return boolean
     */
    public function recordCount()
    {
        return $this->affected_rows;
    }

    /**
     * Remover possivel sql injection
     * Retorna o valor entre ''
     * @param string `$theValue` Valor a ser convertido
     * @access public
     */
    public static function getSQLValueString($theValue)
    {
        $theValue = str_replace(chr(39), '´', $theValue == null ? '' : $theValue);
        $theValue = addslashes($theValue);
        $theValue = (trim($theValue) != "") ? "'" . trim($theValue) . "'" : "''";
        return $theValue;
    }

    /**
     * Corrigi o BUG das querys contra injections e retorna sem '
     * @param string `$theValue` Valor a ser convertido
     * @access public
     */
    public function getSQLValue($theValue)
    {
        return TQuery::get_sql_value($theValue);
    }

    /**
     * Corrigi o BUG das querys contra injections e coloca o valor entre %% para usar com LIKE
     * @param string `$theValue` Valor a ser convertido
     * @access public
     */
    public function getSQLValueStringLike($theValue)
    {
        $theValue = addslashes($theValue);
        $theValue = (trim($theValue) != "") ? "'%" . trim($theValue) . "%'" : "''";
        return $theValue;
    }

    /**
     * Corrigi o BUG das querys contra injections e coloca o valor entre %% para usar com LIKE
     * @param string `$theValue` Valor a ser convertido
     * @access public
     */
    public function getSQLValueStringLikeS($theValue)
    {
        $theValue = addslashes($theValue);
        $theValue = (trim($theValue) != "") ? "'" . trim($theValue) . "%'" : "''";
        return $theValue;
    }
    /**
     * Corrigi o BUG das querys contra injections e retorna sem '
     * @param string `$theValue` Valor a ser convertido
     * @access public
     */
    public static function get_sql_value($theValue)
    {
        $theValue = str_replace(chr(39), '´', $theValue);
        $theValue = addslashes($theValue);
        return $theValue;
    }
    /**
     * Usa um array com ':campo' => 'valor' e substitui toda string igual a `:campo` pelo seu valor
     *
     * @param array `$list_replace` Array contendo a lista de chave valor a ser substituido
     * @param array `$str` SQL com as chaves a serem substituidas
     * @return string
     */
    public static function replace_array_sql($list_replace = [], $str = '')
    {
        foreach ($list_replace as $key => $value) {
            $str = str_replace($key, chr(39) . $value . chr(39), $str);
        }
        return $str;
    }

    /**
     * Salva um xml com o local e query lenta registrada
     *
     * @return boolean
     *
     */
    public function save_slow_query()
    {
        !file_exists(storage_path('logs/tquery_slow/')) ? mkdir(storage_path('logs/tquery_slow/'), 0777, true) : null;

        $file_name = "QuerySlow_" . str_replace(".php", "", basename($this->execute_last_file)) . "_" . date('Ymd_His').".qry";

        $content = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $content .= "<query_slow>\n";
        $content .= "    <uuid>" . $this->getUUID() . "</uuid>  \n";
        $content .= "    <datetime>" . date('Y-m-d H:i:s') . "</datetime>  \n";
        $content .= "    <file>" . $this->execute_last_file . "</file>  \n";
        $content .= "    <line>" . $this->execute_last_line . "</line>  \n";
        $content .= "    <affected_rows>" . $this->affected_rows . "</affected_rows>\n";
        $content .= "    <time_execute>" . $this->time_execute . "</time_execute>\n";
        $content .= "    <data>\n" . json_encode($this->socket) . "\n    </data>\n";
        !empty($this->connection) ? $content .= "    <connection>" . $this->connection. "</connection>\n" : null;
        !empty($this->sql) ? $content .= "    <query_sql>\n" . $this->sql. "\n</query_sql>\n" : null;
        $content .= "\n</query_slow>\n\n";

        return file_put_contents(storage_path('logs/tquery_slow/' . $file_name ), $content);
    }
}
