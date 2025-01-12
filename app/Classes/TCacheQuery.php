<?php
namespace App\Classes;

use App\Classes\TMyError;
use App\Classes\TObject;
use App\Models\CacheQuery;
use Illuminate\Support\Facades\DB;

/**
 *  Cria cache temporarios de resultados de querys sql passados
 * @since 21/08/2024
 * @author NatanFiuza <n@taniel.com.br>
 *
 */
class TCacheQuery extends TObject
{

    const TIMEOUT_CACHE = 120;
    private $timeout_cache;

    public function __construct($timeout_cache = 0)
    {
        $this->timeout_cache = $timeout_cache> 0 ? $timeout_cache : self::TIMEOUT_CACHE ;
    }

    /**
     * Cria o registro de cache e retorna um array do seu conteudo
     *
     * @param mixed $name
     * @param mixed $chave
     * @param mixed $sql
     * @param string $connection
     *
     * @return array
     *
     */
    private function cache_check($name, $chave, $sql, $connection = '', $file = '', $line = '')
    {

        $chave = MD5(trim($chave . strtolower($name)));
        $name = trim($name);

        if (CacheQuery::where('name', $name)->where('key', $chave)->exists()) {
            $cachequery = CacheQuery::where('name', $name)->where('key', $chave)->get()[0]->toArray();

            if (((int) $cachequery['timestamp'] + (int) $this->timeout_cache) < time()) {
                CacheQuery::where('name', $name)->where('key', $chave)->delete();
                return (array) self::cache_save_struct($name, $chave, $sql, $connection, $file, $line);
            } else {
                return [
                    'uuid' => $cachequery['uuid'],
                    'timestamp' => $cachequery['timestamp'],
                    'key' => $cachequery['key'],
                    'name' => $cachequery['name'],
                    'connection' => $cachequery['connection'],
                    'file' => $cachequery['file'],
                    'line' => $cachequery['line'],
                    'sql' => $cachequery['sql'],
                    'data' => json_decode($cachequery['data']),
                ];
            }

        }

        return (array) self::cache_save_struct($name, $chave, $sql, $connection, $file, $line);

    }
    /**
     * Executa a query e salva o registro do cache no banco de dados
     *
     * @param mixed $name
     * @param mixed $chave
     * @param mixed $sql
     * @param string $connection
     *
     * @return array Estrutura do registro de cache
     *
     */
    private function cache_save_struct($name, $chave, $sql, $connection = '', $file = '', $line = '')
    {

        CacheQuery::whereRaw("(timestamp+" . (int) $this->timeout_cache . " ) < " . time())->delete();

        $query = $this->run_query($sql, $connection);
        // if (!$query) {
        //     dd([$name,$chave,$sql,$connection,$file,$line]);
        //     throw new \Exception("Erro ao executar a query", 1);
        // }

        $data = [
            'uuid' => TObject::get_uuid(),
            'timestamp' => time(),
            'key' => (string) $chave,
            'name' => (string) $name,
            'connection' => (string) $connection,
            'file' => (string) $file,
            'line' => (string) $line,
            'sql' => (string) TQuery::get_sql_value($sql),
            'data' => $query,
        ];

        $cachequery = new CacheQuery();
        $cachequery->uuid = $data['uuid'];
        $cachequery->timestamp = $data['timestamp'];
        $cachequery->key = $data['key'];
        $cachequery->name = $data['name'];
        $cachequery->connection = $data['connection'];
        $cachequery->file = $data['file'];
        $cachequery->line = $data['line'];
        $cachequery->sql = $data['sql'];
        $cachequery->data = json_encode($data['data']);
        $cachequery->save();

        return $data;
    }
    /**
     * Executa um query sql e retorna o seu resultado
     *
     * @param mixed $sql Sql a ser executado
     * @param mixed $connection='' Conexcao com o banco de dados
     *
     * @return array
     *
     */
    private static function run_query($sql, $connection = '')
    {
        if (empty(trim($sql))) {
            $error = new TMyError("Query vazia", 0);
            $error->query_sql = '';
            $error->error_line = __FILE__;
            $error->error_file = __LINE__;
            $error->class_identify = __CLASS__ . "::" . __FUNCTION__;
            $error->exception_message = "Query vazia em run_query";
            $error->class_name = __CLASS__;
            $error->function_name = __FUNCTION__;
            $error->exception_filename = __FILE__;
            $error->save_error_file();

            return false;
        }

        try {
            // $connection == ''
            // ? $query = DB::select($sql) :
            // $query = DB::connection($connection)->select($sql);
            $query = new TQuery($connection);
            $query->add($sql)->execute(__FILE__, __LINE__);

        } catch (\Exception $e) {
            $error = new TMyError("Erro ao executar query", 0);
            $error->query_sql = $sql;
            $error->error_line = $e->getLine();
            $error->error_file = $e->getFile();
            $error->class_identify = __CLASS__ . "::" . __FUNCTION__;
            $error->exception_message = "Code: " . $e->getCode() . "\nError:\n" . $e->getMessage() . (empty(trim($connection)) == '' ? '' : "\nConnection: $connection\n") . "\n\nTrace:\n" . $e->getTraceAsString();
            $error->class_name = __CLASS__;
            $error->function_name = __FUNCTION__;
            $error->exception_filename = __FILE__;
            $error->save_error_file();

            return false;
        }

        return (array) $query->fetch_all();

    }

    /**
     * Cria o cache de uma query passada e retorna seu resultado
     *
     * @param string $name
     * @param string $chave
     * @param string $sql
     *
     * @return [type]
     *
     */
    public function cache($name, $chave, $sql, $connection = '', $has_manifest = false, $file = '', $line = '')
    {

        return $this->cache_check($name, $chave, $sql, $connection, $file, $line)['data'];

    }

}
