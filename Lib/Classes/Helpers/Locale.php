<?php
// @author: C.A.D. BONDJE DOUE
// @file: Locale.php
// @date: 20230303 09:51:26
namespace igk\js\Vue3_i18n\Helpers;

use IGK\Controllers\BaseController;
use IGK\Helper\IO;
use IGK\Helper\ViewHelper;
use IGKException;
use IGK\System\Exceptions\ArgumentTypeNotValidException;
use ReflectionException;

///<summary></summary>
/**
 * 
 * @package igk\js\Vue3_i18n\Helper
 */
class Locale
{

    public static function LoadVueI18n(BaseController $ctrl)
    {
        if (is_file($file = $ctrl->configFile("vue3-i18n"))) {
            return include($file);
        }
        if (is_dir($dir = $ctrl->configDir() . "/vue3-i18n")) {
            //will be use as base locale directory 
            return self::LoadLocaleFromDir($ctrl, $dir);
        }
        return null;
    }
    /**
     * load controller locales
     * @param BaseController $ctrl 
     * @return mixed 
     * @throws IGKException 
     * @throws ArgumentTypeNotValidException 
     * @throws ReflectionException 
     */
    public static function LoadLocale(BaseController $ctrl, bool $useglobal_resource = false, string $defaultLangKey='en')
    {
        if ($pres = self::_GenLocale($ctrl, $useglobal_resource)){
            $lk = $defaultLangKey;
            $core = isset($pres[$lk]) ? $pres[$lk] : [];
            // treat en loag
            foreach($pres as $k => $v){
                if($k == $lk)continue; 
                if ($dif = array_diff(array_keys($v), array_keys($core))){ 
                    $core = array_merge($core, array_combine($dif, $dif));
                }
            }
            $pres[$lk] = $core;
        }
        return $pres;
    }
    /**
     * internal use to get locale
     * @param BaseController $ctrl 
     * @param bool $useglobal_resource 
     * @return array|null 
     * @throws IGKException 
     * @throws ArgumentTypeNotValidException 
     * @throws ReflectionException 
     */
    private static function _GenLocale(BaseController $ctrl, bool $useglobal_resource = false)
 {
        $pres = null;
        if ($useglobal_resource){
            $pres = self::LoadPresxFromDir($ctrl, IGK_LIB_DIR."/Default/Lang") ?? [];
        }
        if (is_dir($dir = ($ctrl->configDir() . '/Lang'))) {
            
            if (self::_MergeLocale($pres, self::LoadPresxFromDir($ctrl, $dir)) && $pres) {
                if ($g = self::LoadVueI18n($ctrl)) { 
                    self::_MergeLocale($pres, $g);
                }
                return $pres;
            }
        }
        if (self::_MergeLocale($pres, self::LoadVueI18n($ctrl)) && $pres){
            return $pres;
        }
        return null;      
    }
    private static function _MergeLocale(& $pres, $g){
        if (!$g){
            return $pres;
        }
        array_map(function($a, $k)use(& $pres){ 
            if (isset($pres[$k])){
                $pres[$k] = array_merge($pres[$k], $a);
            }else{
                $pres[$k] = $a;
            }
        }, $g, array_keys($g));  
        return $pres;
    }
    public static function LoadLocaleFromDir(BaseController $ctrl, $dir)
    {
        $data = [];
        foreach (IO::GetFiles($dir, "/\.(json|php)$/") as $file) {
            $ext = igk_io_path_ext($file);
            $locale = igk_io_basenamewithoutext($file);
            $s = igk_getv($data, $locale) ?? [];
            switch ($ext) {
                case 'json':
                    $s = array_merge($s, (array)json_decode(file_get_contents($file)));
                    break;
                case 'php':
                    if ($pm = ViewHelper::Include($file, compact("ctrl", "locale"))) {
                        $s = array_merge($s, $pm);
                    }
                    break;
            }
            $data[$locale] = $s;
        }
        return $data;
    }
    public static function LoadPresxFromDir(BaseController $ctrl, string $dir)
    {
        $data = [];
        $__fc_load_res = function (&$l, $ctrl) { // $l 
            extract(func_get_args());
            include func_get_arg(2);
        };
        foreach (IO::GetFiles($dir, "/\.(presx)$/") as $file) {
            $ext = igk_io_path_ext($file);
            $locale = igk_str_rm_start(igk_io_basenamewithoutext($file), 'lang.');
            $s = igk_getv($data, $locale) ?? [];
            $l = null;
            if (isset($data[$locale]))
                $l = &$data[$locale];
            else
                $l = &$s;
            $__fc_load_res($l, $ctrl, $file);
            $data[$locale] = $l;
        }
        return $data;
    }
}
