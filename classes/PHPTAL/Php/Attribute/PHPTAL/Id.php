<?php
/**
 * PHPTAL templating engine
 *
 * PHP Version 5
 *
 * @category HTML
 * @package  PHPTAL
 * @author   Laurent Bedubourg <lbedubourg@motion-twin.com>
 * @author   Kornel Lesiński <kornel@aardvarkmedia.co.uk>
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @version  SVN: $Id$
 * @link     http://phptal.motion-twin.com/ 
 */
/**
 * @package PHPTAL.php.attribute.phptal
 * @author Laurent Bedubourg <lbedubourg@motion-twin.com>
 */
class PHPTAL_Php_Attribute_PHPTAL_ID extends PHPTAL_Php_Attribute
{
    private $var;
    public function start(PHPTAL_Php_CodeWriter $codewriter)
    {
        // retrieve trigger
        $this->var = $codewriter->createTempVariable();        
                
        $codewriter->doSetVar(
            $this->var, 
            '$tpl->getTrigger('.$codewriter->str($this->expression).')'
        );

        // if trigger found and trigger tells to proceed, we execute
        // the node content
        $codewriter->doIf($this->var.' && 
            '.$this->var.'->start('.$codewriter->str($this->expression).', $tpl) === PHPTAL_Trigger::PROCEED');
    }

    public function end(PHPTAL_Php_CodeWriter $codewriter)
    {
        // end of if PROCEED
        $codewriter->doEnd();
        
        // if trigger found, notify the end of the node
        $codewriter->doIf($this->var);
        $codewriter->pushCode(
            $this->var.'->end('.$codewriter->str($this->expression).', $tpl)'
        );
        $codewriter->doEnd();
        $codewriter->recycleTempVariable($this->var);
    }
}

