<?php

class Block
{
	private $_html;

	public function render($html)
	{
		if (!$this->validation($html)) {
			die();
		} else {
			echo $html . "<br/>" . $this->validation($html);
		}
	}
}

class Text extends Block
{
	public function validation($text)
	{
		return $text . ' - valid(Text)';
	}
}

class Image extends Block
{
	public function validation($text)
	{
		return $text . ' - valid(Image)';
	}
}

class Button extends Block
{
	public function validation($text)
	{
		return $text . " - valid(Button)";
	}
}

$text = new Text();

echo $text->render('<b>hello</b>');
