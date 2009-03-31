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
require PHPTAL_DIR.'PHPTAL/Php/Attribute.php';
require PHPTAL_DIR.'PHPTAL/Namespace/TAL.php';
require PHPTAL_DIR.'PHPTAL/Namespace/METAL.php';
require PHPTAL_DIR.'PHPTAL/Namespace/I18N.php';
require PHPTAL_DIR.'PHPTAL/Namespace/PHPTAL.php';

/** 
 * @package PHPTAL
 */
abstract class PHPTAL_NamespaceAttribute
{
    /** 
     * @param $name string The attribute name
     * @param $priority int Attribute execution priority
     */
    public function __construct($local_name, $priority)
    {
        $this->local_name = $local_name;
        $this->_priority = $priority;
    }

    /**
     * @return string
     */
    public function getLocalName()
    { 
        return $this->local_name; 
    }
    
    public function getPriority(){ return $this->_priority; }
    public function getNamespace(){ return $this->_namespace; }
    public function setNamespace(PHPTAL_Namespace $ns){ $this->_namespace = $ns; }

    public function createAttributeHandler(PHPTAL_DOMElement $tag, $expression)
    {
        return $this->_namespace->createAttributeHandler($this, $tag, $expression);
    }
    
    private $local_name;         /* Attribute name without the namespace: prefix */
    private $_priority;     /* [0 - 1000] */
    private $_namespace;    /* PHPTAL_Namespace */
}

/** 
 * @package PHPTAL
 */
class PHPTAL_NamespaceAttributeSurround extends PHPTAL_NamespaceAttribute 
{
}

/** 
 * @package PHPTAL
 */
class PHPTAL_NamespaceAttributeReplace extends PHPTAL_NamespaceAttribute 
{
}

/** 
 * @package PHPTAL
 */
class PHPTAL_NamespaceAttributeContent extends PHPTAL_NamespaceAttribute 
{
}

/** 
 * @package PHPTAL
 */
abstract class PHPTAL_Namespace
{   
    private $prefix, $namespace_uri;

    public function __construct($prefix, $namespace_uri)
    {
        $this->_attributes = array();
        $this->prefix = $prefix;
        $this->namespace_uri = $namespace_uri;
    }

    public function getPrefix()
    {
        return $this->prefix;
    }

    public function getNamespaceURI()
    {
        return $this->namespace_uri;
    }

    public function hasAttribute($attributeName)
    {
        return array_key_exists(strtolower($attributeName), $this->_attributes);
    }

    public function getAttribute($attributeName)
    {
        return $this->_attributes[strtolower($attributeName)];
    }
    
    public function addAttribute(PHPTAL_NamespaceAttribute $attribute)
    {
        $attribute->setNamespace($this);
        $this->_attributes[strtolower($attribute->getLocalName())] = $attribute;
    }

    public function getAttributes()
    {
        return $this->_attributes;
    }

    abstract public function createAttributeHandler(PHPTAL_NamespaceAttribute $att, PHPTAL_DOMElement $tag, $expression);

    protected $_attributes;
}

/** 
 * @package PHPTAL
 */
class PHPTAL_BuiltinNamespace extends PHPTAL_Namespace
{
    public function createAttributeHandler(PHPTAL_NamespaceAttribute $att, PHPTAL_DOMElement $tag, $expression)
    {
        $name = $att->getLocalName();
        $name = str_replace('-', '', $name);
        
        $class = 'PHPTAL_Php_Attribute_'.$this->getPrefix().'_'.$name;
        $result = new $class($tag, $expression);
        return $result;
    }
}

?>
