<?php

namespace App\Classes;

use App\Classes\TMyString;
use App\Classes\TObject;

/**
 * Controle de erros
 *
 *
 * @since 10/07/2006
 * @author Nataniel Fiuza <natan.fiuza@gmail.com>
 * @version 1.0
 * @package system
 * @copyright NatanFiuza.dev.br
 *
 */
class TMyError extends TObject
{

    /**
     * Nome do arquivo que voce deseja salvar
     *
     * @var string $fileName
     * @see grava()
     * @access private
     */

    private $fileName = 'log_file.log';

    /**
     * Lista de erros comuns do sistema
     * @var array $errorList
     *
     */
    public $errorList = array();

    /**
     * Recebe a mensagem de erro
     * Formatada com todos os detalhes
     * @var string
     */
    public $msgErro;

    /**
     * Recebe mensagem passada na construcao do objeto
     *
     * @var string
     */
    public $msg;

    /**
     * Linha do arquivo com o erro
     *
     * @var string
     */
    public $error_line;

    /**
     * Nome do arquivo com erro
     *
     */
    public $error_file;

    /**
     * Caso seja um erro SQL recebe a query que foi usada
     * @var string
     */
    public $query_sql = '';

    /**
     * Numero do erro
     *
     */
    public $numErro;

    /**
     * Nome da classe onde ocorreu o erro
     */
    public $class_identify;

    /**
     * Informar $e->getMessage() em uma ocorrencia
     *
     */
    public $exception_message;

    public $class_name;
    public $function_name;
    public $exception_filename;

    /**
     *
     * Metodo main, cria a lista de erros para o atributo  ErrorList
     *
     */
    public function __construct($msg = "Erro encontrado.", $erroNum = 0, $query_sql = '', $file = __FILE__, $line = __LINE__, $class_identify = __CLASS__, $exception_message = '')
    {

        $this->fileName = 'file_log.log';
        $this->errorList[0] = "Erro não identificado";
        $this->errorList[1] = "O código de erro não foi encontrado em class.TMyError.ErrorList";
        $this->errorList[2] = "O valor de class.TDataBase.driver não foi definido corretamente. Valores possiveis: <BR>mysql -> Banco de dados MySql <br>sqlsrv -> Banco de dados Microsoft SQL Server <br>pgsql -> Banco de dados PostGreSql";
        $this->errorList[3] = "O parametro :<#1> não foi encontrado em class.TQuery.query";
        $this->errorList[4] = "Você não tem acesso a esta area do sistema. ";
        $this->errorList[5] = "Erro na query do banco de dados";
        $this->errorList[6] = "Erro na conexão do banco de dados";
        $this->errorList[7] = "Erro interno controlado por um try";
        $this->errorList[8] = "Erro ao executar função MultiQuery";
        $this->errorList[9] = "Erro class.TObject: um atributo não foi definido.";
        $this->errorList[10] = "Você não tem privilégio para acessar esta página do sistema.";
        $this->errorList[11] = "Erro ao executar um PDO::beginTransaction ";
        $this->errorList[12] = "Erro ao executer função mysqli_query";
        $this->errorList[13] = "Não foi possivel inserir o registro, por favor tente novamente.";
        $this->errorList[14] = "Não foi possivel alterar o registro, por favor tente novamente.";
        $this->errorList[15] = "";
        $s = '';
        if ($erroNum <= 14) {
            $s = "-------------------------------------------------------------------------------\n";
            $s .= "       ERRO: #$erroNum  \n";
            $s .= "       FILE: $file \n";
            $s .= "       LINE: $line\n";
            $s .= "Msg Default:" . $this->errorList[$erroNum] . "\n";
            $s .= "   Mensagem: $msg\n";
            !empty($this->query_sql) ? $s .= "  Sql Query:" . $this->query_sql . "\n" : null;
            $s .= "------------------------------------------------------------------------------\n\n";
        }
        $this->msgErro = $s;
        $this->msg = $msg;
        $this->query_sql = $query_sql;
        $this->error_line = $line;
        $this->error_file = $file;
        $this->numErro = $erroNum;
        $this->class_identify = $class_identify;
        $this->exception_message = $exception_message;
        !file_exists(storage_path('logs/')) ? mkdir(storage_path('logs/'), 0777, true) : null;
        !file_exists(storage_path('logs/' . env('PATH_TMYERROR', 'tmyerror'))) ? mkdir(storage_path('logs/' . env('PATH_TMYERROR', 'tmyerror')), 0777, true) : null;

    }

    /**
     * Retorna a string do erro de acordo com codigo passado
     *
     */
    public function getError($pNumErro, $pArg = "")
    {
        if ($pNumErro > count($this->errorList) || $pNumErro < 0) {
            return '';
        }
        return str_replace("<#1>", $pArg, $this->errorList[$pNumErro]);
    }

    /**
     * Imprime a handler do erro encontrado
     *
     */
    public function error($isprint = true)
    {
        $msg = "<br>\n<br>" . str_replace("\n", "\n<br>", $this->msgErro) . "<br>";
        if ($isprint) {
            die($msg);
        }
        return str_replace("<br>", "", $msg);
    }

    /**
     * Grava no arquivo de log
     *
     */
    public function saveLog()
    {
        $content = file_get_contents($this->fileName);
        $content .= "[" . date('d-m-Y H:i:s') . "] ";
        $content .= "FL: " . $this->error_file . "/" . $this->error_line . " | ";
        $content .= $this->class_identify . " | ";
        $content .= $this->class_name . " | ";
        $content .= $this->function_name . " | ";
        $content .= $this->exception_message . " | ";
        $content .= ($this->numErro <= count($this->errorList) ? $this->errorList[$this->numErro] : $this->numErro) . " | ";
        $content .= "\n\r";
        file_put_contents($this->fileName, $content);
    }

    /**
     * Salva o XML com o handle de erro capturando todas as informacoes
     *
     * @param string $file_name
     * @param string $extension
     *
     * @return bool
     *
     */
    public function save_error_file($file_name = "error_file", $extension = '.err')
    {
        !file_exists(storage_path('logs/tmyerror/')) ? mkdir(storage_path('logs/tmyerror/'), 0777, true) : null;
        putenv('PATH=C:\Program Files\Git\bin');

        $output = shell_exec("git log -1 --pretty=\"format:<hash_long>%H</hash_long><hash_short>%h</hash_short><commit_text>%s</commit_text><commit_text_snake>%f</commit_text_snake><tag_b>%b</tag_b><tag_d>%d</tag_d><tag_e>%e</tag_e><author_name>%an</author_name><author_email>%ae</author_email><data_reg_ext>%aD</data_reg_ext><time_ago>%ar</time_ago><data_reg>%ai</data_reg>\" " . $this->error_file);
        $output_annotate = shell_exec("git annotate  -L $this->error_line,$this->error_line --line-porcelain $this->error_file");
        $remove_hash_annotate = function ($output_annotate) {
            $annotate = explode("\n", $output_annotate);
            return is_array($annotate) ? explode(' ', $annotate[0])[0] : '';
        };

        $remove_data_annotate = function ($output_annotate, $data_name) {
            $annotate = explode("\n", $output_annotate);
            foreach ($annotate as $value) {
                if (substr($value, 0, strlen($data_name) + 1) == trim($data_name) . ' ') {
                    return str_replace(['<', '>'], '', substr($value, strlen($data_name) + 1, strlen($value)));
                }
            }
        };

        $output_remote = shell_exec("git remote  -v");

        $remove_url_remote = function ($output_remote) {
            $remote = explode("\n", $output_remote);
            if (!is_array($remote)) {
                return '';
            }
            $id_url = explode(":", $remote[0]);
            if (!is_array($id_url) || sizeof($id_url) <= 1) {
                return '';
            }
            $id_url = substr($id_url[1], 0, strpos($id_url[1], '.git'));
            return $id_url;
        };
        $git_remote_id = $remove_url_remote($output_remote);
        $file_name == "error_file" ? $file_name = "Error_" . str_replace(".php", "", basename($this->exception_filename)) . "_" . str_replace(".php", "", basename($this->error_file)) . "_" . date('Ymd_His') : null;
        $content = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $content .= "<error_log>\n";
        $content .= "    <uuid>" . $this->getUUID() . "</uuid>  \n";
        $content .= "    <datetime>" . date('Y-m-d H:i:s') . "</datetime>  \n";
        $content .= "    <error_num>" . $this->numErro . "</error_num>  \n";
        $content .= "    <description>" . ($this->numErro <= count($this->errorList) ? $this->errorList[$this->numErro] : $this->numErro) . "</description>\n";
        $content .= "    <file>" . $this->error_file . "</file>\n";
        $content .= "    <line>" . $this->error_line . "</line>\n";
        $content .= empty($git_remote_id) ? "    <git_remote_id />\n" : "    <git_remote_id>" . $git_remote_id . "</git_remote_id>\n";
        $content .= "    <hash_file_long>" . TMyString::cut_tag_value('hash_long', $output) . "</hash_file_long>\n";
        $content .= "    <hash_file_short>" . TMyString::cut_tag_value('hash_short', $output) . "</hash_file_short>\n";
        $content .= "    <commit_file_text>" . TMyString::cut_tag_value('commit_text', $output) . "</commit_file_text>\n";
        $content .= "    <commit_file_date>" . TMyString::cut_tag_value('data_reg', $output) . "</commit_file_date>\n";
        $content .= "    <author_name_file>" . TMyString::cut_tag_value('author_name', $output) . "</author_name_file>\n";
        $content .= "    <author_email_file>" . TMyString::cut_tag_value('author_email', $output) . "</author_email_file>\n";
        $content .= "    <hash_line_long>" . $remove_hash_annotate($output_annotate) . "</hash_line_long>\n";
        $content .= "    <hash_line_short>" . substr($remove_hash_annotate($output_annotate), 0, 10) . "</hash_line_short>\n";
        $content .= "    <commit_line_text>" . $remove_data_annotate($output_annotate, 'summary') . "</commit_line_text>\n";
        $content .= "    <commit_line_date>" . date('Y-m-d H:i:s', $remove_data_annotate($output_annotate, 'author-time')) . "</commit_line_date>\n";
        $content .= "    <author_name_line>" . $remove_data_annotate($output_annotate, 'author') . "</author_name_line>\n";
        $content .= "    <author_email_line>" . $remove_data_annotate($output_annotate, 'author-mail') . "</author_email_line>\n";
        $content .= "    <class_name>" . $this->class_name . "</class_name>\n";
        $content .= "    <function_name>" . $this->function_name . "</function_name>\n";
        $content .= "    <class_identify>" . $this->class_identify . "</class_identify>\n";
        $content .= "    <exception_message>" . $this->exception_message . "</exception_message>\n";
        $content .= $this->msg != '' ? "    <message>\n" . $this->msg . "\n    </message>\n" : "    <message/>\n";
        !empty($this->query_sql) ? $content .= "    <query_sql>" . $this->query_sql . "</query_sql>\n" : $content .= "    <query_sql/>\n";
        $content .= "</error_log>\n\n";
        $file_name = str_replace(['\\','/'],'',$file_name);
        return file_put_contents(storage_path('logs/tmyerror/' . str_replace("'",'',$file_name . $extension)), $content);
    }
}
