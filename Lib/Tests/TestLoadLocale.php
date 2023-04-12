<?php
// @author: C.A.D. BONDJE DOUE
// @filename: TestLoadLocale.php
// @date: 20230308 15:39:18
// @desc: loading locale

namespace igk\js\Vue3_i18n\Tests;

use CarRentalController;
use IGK\Controllers\SysDbController;
use igk\js\common\JSExpression;
use igk\js\common\IJSExpressionOptions;
use igk\js\Vue3\Libraries\i18n\Vuei18n;
use igk\js\Vue3_i18n\Helpers\Locale;
use IGK\Tests\Controllers\ModuleBaseTestCase;

class TestLoadLocale extends ModuleBaseTestCase{
    static function setUpBeforeClass(): void
    {
        igk_require_module(\igk\js\common::class);
        igk_require_module(\igk\js\Vue3::class);
    }
    public function test_loading_one(){
        $l['not allowed to upload more than {0} file(s)'] = 'Vous n êtes pas autorisé à téléverser plus de {0} fichier(s).';
        
       
        $s = JSExpression::Stringify((object)$l, (object)['detectMethod'=>false]);

        $this->assertEquals(
            '{"not allowed to upload more than {0} file(s)":"Vous n êtes pas autorisé à téléverser plus de {0} fichier(s)."}'
        , $s);
        
    }
    public function test_loading_locale_system_ctrl(){
        $g = Locale::LoadLocale(SysDbController::ctrl(),false);        
        $this->assertEquals(
            null,$g
        , "system locale with non global must be empty");        
    }
    public function _test_loading_ctrl(){
        $g = Locale::LoadLocale( CarRentalController::ctrl(),false);      
        /**
         * @var IJSExpressionOptions $obj
         */
        $obj = igk_createobj();  
        $obj->detectMethod = false;

        $this->assertEquals(
            'dddd', JSExpression::Litteral(JSExpression::Stringify($g, $obj))->getValue()
        , "failed load controller locale");        
    }

    public function test_loading_ctrl_load(){        
 
        $this->assertStringContainsString(
            '"locale"',
            Vuei18n::VueRenderI18nLocaleSetting('i18n', 'jesais', CarRentalController::ctrl(), false, ['default_lang'=>'fr']),
            "failed load controller locale");   

    }
}



