<?php

abstract class TemplateAbstract 
{
	//Счетчик подключеных плагинов к блоку
	protected static $totalConnPlugin = 0;

    abstract public function connectedPlugin();
    abstract public function action($c);

    final public function show($block) {
    	$this->connectedPlugin();
        $content = $block->getContent();
        $result = $this->action($content);
        
        return $result;
    }
}

class Plugin1 extends TemplateAbstract 
{
	public function action($content)
	{
		return $this->setComments($content);
	}

    private function setComments($content)
    {
    	$str = "<!-- Begin block. ObjId: " . $this->objId() . 
    		" Object name: " . $this->objName() . 
    		" Length: " . $this->contentLength($content) . 
    		" connected plugin: " . $this->connectedPlugin() .
    		" -->" . $content . "<!-- End block -->";
    	return $str;
    }

    public function objName()
    {
    	return get_class($this);
    }

    public function objId()
    {
    	return spl_object_hash($this);
    }
    public function contentLength($content)
    {
    	return strlen($content);
    }

    public function connectedPlugin()
    {
    	return ++self::$totalConnPlugin;
    }
}

class Plugin2 extends TemplateAbstract 
{
	private $_tags;

	public function __construct($tags)
	{
		$this->_tags = $tags;
	}

	public function action($content)
	{
		return $this->find($content);
	}

	private function find($content)
	{
		preg_match_all("/(<$this->_tags><\/$this->_tags>)/", $content, $match);
		$parts = explode("<$this->_tags></$this->_tags>", $content);
		$parts = implode(' ', $parts);
		$newContent = $this->addCutBlocks($parts, $match);

		return $newContent;
	}

	private function addCutBlocks($content, $blocks)
	{
		foreach ($blocks[0] as $key => $value) {
			$content .= $value;
		}
		return $content;
	}

    public function connectedPlugin()
    {
    	return ++self::$totalConnPlugin;
    }
}

class Block 
{
    private $_content;

    public function __construct($content_in) {
        $this->_content = $content_in;
    }

    public function getContent() 
    {
    	return $this->_content;
    }

}

 
 $block = new Block('<div>PHP <div></div><div></div>for <div></div>Cats Larry <div></div>Truett</div>');



$plugin = new Plugin1();  

$plugin = new Plugin1(); 
$plugin2 = new Plugin2('div');




?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

	<?=$plugin->show($block)?>
	<br/><hr/><br/>
	<?=$plugin2->show($block)?>

</body>
</html>