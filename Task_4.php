<?php

interface Iterator1
{
    public function getKey();
    public function getCurrentBook();
    public function hasNextBook();
    public function getNextBook();
}

class Book 
{
    private $_author;
    private $_title;

    function __construct($title_in, $author_in) {
        $this->_author = $author_in;
        $this->_title  = $title_in;
    }

    function getAuthor() {
        return $this->_author;
    }

    function getTitle() {
        return $this->_title;
    }

    function getAuthorAndTitle() {
        return $this->getTitle() . ' by ' . $this->getAuthor();
    }
}

class BookList 
{
    private $_books = array();
    private $_bookCount = 0;

    public function __construct() {
    }

    public function getBookCount() {
        return $this->_bookCount;
    }

    private function setBookCount($newCount) {
        $this->_bookCount = $newCount;
    }

    public function getBook($bookNumberToGet) {
        if ((is_numeric($bookNumberToGet)) && 
            ($bookNumberToGet <= $this->getBookCount())) {
            return $this->_books[$bookNumberToGet];
            } else {
            return NULL;
            }
    }

    public function addBook(Book $book_in) {
        $this->setBookCount($this->getBookCount() + 1);
        $this->_books[$this->getBookCount()] = $book_in;

        return $this->getBookCount();
    }
    //Метод вывода: по уровням и в глубь.
    public function printByBreadth()
    {
        $booksIterator = new BookListIterator($this);
        //Создаем обект очереди(встроенно в PHP). 
        $q = new SplQueue();
        //Помещаем первый обект в очередь
        $q->push($this->getBook(1));

        while (!$q->isEmpty()) {
            //Удаляем обект из очереди
            $q->pop();
            if ($booksIterator->hasNextBook()) {

            $book = $booksIterator->getNextBook();
            $key = $booksIterator->getKey();
            $method = explode('::', __METHOD__);

            $q->push($booksIterator->getCurrentBook());

            echo "<br/>ID: {$booksIterator->getKey()} (Method: " . $method[1] . ") Book: <br/>{$book->getAuthorAndTitle()}<br/>";
            }  
        }
    }
    //Метод вывода: рекурсивно в глубь
    public function printByDepth()
    {
        $booksIterator = new BookListIterator($this);

        while ($booksIterator->hasNextBook()) {
            $book = $booksIterator->getNextBook();
            $method = explode('::', __METHOD__);

            echo "<br/>ID: {$booksIterator->getKey()} (Method: " . $method[1] . ") Book: <br/>{$book->getAuthorAndTitle()}<br/>";
        }
    }
}

class BookListIterator implements Iterator1 {
    protected $bookList;
    protected $currentBook = 0;

    public function __construct(BookList $bookList_in) {
        $this->bookList = $bookList_in;
    }

    public function getKey()
    {
        return $this->currentBook;
    }

    public function getCurrentBook() {
        if (($this->currentBook > 0) && 
            ($this->bookList->getBookCount() >= $this->currentBook)) {
            return $this->bookList->getBook($this->currentBook);
        }
    }

    public function getNextBook() {
        if ($this->hasNextBook()) {
            return $this->bookList->getBook(++$this->currentBook);
        } else {
            return NULL;
        }
    }

    public function hasNextBook() {
        if ($this->bookList->getBookCount() > $this->currentBook) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}




$firstBook = new Book('Core PHP Programming, Third Edition', 'Atkinson and Suraski');
$secondBook = new Book('PHP Bible', 'Converse and Park');
$thirdBook = new Book('Design Patterns', 'Gamma, Helm, Johnson, and Vlissides');

$books = new BookList();

$books->addBook($firstBook);
$books->addBook($secondBook);
$books->addBook($thirdBook);

 
$booksIterator = new BookListIterator($books);



$books->printByDepth();
echo "<br/>" . str_repeat('****', 12) . "<br/>";
$books->printByBreadth();

?>