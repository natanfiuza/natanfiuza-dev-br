<?php
namespace App\Classes;

use App\Classes\TObject;

/**
 *  Classe de tratamento de strings
 * @since 10/08/2014
 * @author NatanFiuza <n@taniel.com.br>
 *
 */
class TMyString extends TObject
{

    public function convertCharset($str)
    {

        $tmp = $str;
        $str = htmlentities($str, ENT_QUOTES, "UTF-8");
        $str = str_replace("&amp;", "&", $str);
        $str = str_replace("&amp;", "&", $str);
        $str = str_replace("&amp;", "&", $str);
        if (empty($str)) {
            // $str = utf8_encode($tmp);
            $str = htmlentities($str, ENT_QUOTES, "UTF-8");
            $str = str_replace("&amp;", "&", $str);
            $str = str_replace("&amp;", "&", $str);
            $str = str_replace("&amp;", "&", $str);
        }
        return $str;
    }
    /**
     * Recorta um valor especifico entre as tag definida
     * @param string $tag_name recebe o nome da tag
     * @param string $str a string a ser
     */
    public static function cut_tag_value($tag_name = '', $str = '', $content_only = true)
    {
        $tag_name = trim($tag_name);
        $str_return = substr($str, strpos($str, "<$tag_name>") + ($content_only ? strlen("<$tag_name>") : 0), strlen($str));
        $str_return = substr($str_return, 0, strpos($str_return, "</$tag_name>") + ($content_only ? 0 : strlen("</$tag_name>")));
        return $str_return;
    }
}
