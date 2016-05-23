<?php

/**
 * Component - компонент
 * объявляет интерфейс для компонуемых объектов;
 * предоставляет подходящую реализацию операций по умолчанию,
 * общую для всех классов;
 * объявляет интерфейс для доступа к потомкам и управлению ими;
 * определяет интерфейс доступа к родителю компонента в рекурсивной структуре
 * и при необходимости реализует его. Описанная возможность необязательна;
 */
abstract class Component
{
    protected $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    abstract public function add(Component $c);
    abstract public function remove(Component $c);
    abstract public function renderComposition();
    abstract public function renderPlaceholder($c);
}

/**
 * Composite - составной объект
 * определяет поведение компонентов, у которых есть потомки;
 * хранит компоненты-потомки;
 * реализует относящиеся к управлению потомками операции и интерфейс
 */
class Composite extends Component
{
    private $_blocks = array();

    public function add(Component $component)
    {
        $this->_blocks[$component->name] = $component;
    }

    public function remove(Component $component)
    {
        unset($this->_blocks[$component->name]);
    }
    //Рендеринг вего дерева
    public function renderComposition()
    {
        $this->getCompositeName();
        foreach ($this->_blocks as $key => $value) {
             $value->renderComposition();
        }
    }

    public function getCompositeName()
    {
        echo $this->name . '<br />';
    }
    //Рендеринг вложеного блока по placeHolder'у
    public function renderPlaceholder($component)
    {
        $this->_blocks[$component]->renderComposition();       
    }
}

/**
 * Leaf - лист
 * представляет листовой узел композиции и не имеет потомков;
 * определяет поведение примитивных объектов в композиции;
 */
class Leaf extends Component
{
    public function add(Component $c)
    {
        print ("Cannot add to a leaf");
    }

    public function remove(Component $c)
    {
        print("Cannot remove from a leaf");
    }

    public function renderComposition()
    {
        $tab = str_repeat('&nbsp', 4);
        echo $tab . $this->name . '<br />';
    }

    public function renderPlaceholder($component)
    {
        $tab = str_repeat('&nbsp', 4);
        echo $tab . $this->name . '<br />';
    }

}
// Формируем древо
$block = new Composite('Block');

$comp = new Composite('Text block 1');
$comp->add(new Leaf('Button 1'));
$comp->add(new Leaf('Button 2'));
$comp->add(new Leaf('Button 3'));
$block->add($comp);

$comp = new Composite('Text block 2');
$comp->add(new Leaf('Button 1'));
$comp->add(new Leaf('Button 2'));
$comp->add(new Leaf('Text block'));
$block->add($comp);

$comp = new Composite('Images');
$block->add($comp);


$block->renderComposition();
?>

<div style="border:2px solid red"><?=$block->renderPlaceholder('Text block 2');?><div>
