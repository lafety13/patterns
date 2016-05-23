<?php

abstract class Component
{
    abstract public function Operation();
}

class ConcreteComponent extends Component
{
    public function Operation()
    {
        return '<p>Block content</p>';
    }
}

abstract class Decorator extends Component
{
    protected $_component = null;
    
    public function __construct(Component $component)
    {
        $this->_component = $component;
    }
    
    protected function getComponent()
    {
        return $this->_component;
    }
    
    public function Operation()
    {
        return $this->getComponent()->Operation();
    }
}

class addComments extends Decorator
{
    public function Operation()
    {
        $comments = "<!-- Block begin. \n
            Type: {$this->get_class()} \n
            ID: {$this->objectId()} \n
            Length: {$this->contentLength()} -->"  
            . parent::Operation() . 
            "<!-- Block end. \n
            Type: {$this->get_class()} \n
            ID: {$this->objectId()} \n
            Length: {$this->contentLength()} -->";

        return $comments;
    }

    public function get_class()
    {
        return get_class($this);
    }

    public function contentLength()
    {
        $length = strlen(parent::Operation());
        return $length;
    }

    public function objectId()
    {
        return spl_object_hash($this);
    }

}

class addBorder extends Decorator
{
    private $_thickness;
    private $_color;

    public function __construct(Component $component, $thickness, $color)
    {
        parent::__construct($component);
        //Простая валидация
        try { 
            if (is_integer($thickness) && $thickness < 100 && is_string($color)) {
                $this->_thickness = $thickness;
                $this->_color = $color;
            } else {
                throw new Exception('Incorrect input data. Wrong type.');
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            die();
        }
    }

    public function Operation()
    {
        $block = "<div style='border: {$this->_thickness}px solid {$this->_color}'>" . parent::Operation() . "</div>";
       
        return $block;
    }

}

$element = new ConcreteComponent();
$addComm = new addComments($element);
$addBorder = new addBorder($addComm, 5, '#000');

print $addBorder->Operation(); 


