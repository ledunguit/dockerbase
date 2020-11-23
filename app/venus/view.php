<?php
namespace Venus;
/**
 * @property  categories
 */
class View {
    private $title;
    private $keywords;
    private $description;
    private $published;
    private $modified;
    private $layout;
    private $placeholder;

    public function __construct() {
        $this->setLayout(Venus::$config['defaultTemplate']);
        $this->setTitle(isset(Venus::$config['name'])?Venus::$config['name']:'');
        $this->keywords = 'Quan ly sinh vien, qlsv, uit, ledung';
        $this->description = 'Học MVC';
        $this->published = '2020-08-25';
        $this->modified = '2020-08-25';
    }

    public function setLayout($layout) {
        $this->layout = $layout;
    }
    public function getLayout() {
        return $this->layout;
    }
    public function setKeywords($keywords) {
        $this->keywords = $keywords;
    }
    public function getKeywords() {
        return $this->keywords;
    }
    public function setDescription($description) {
        $this->description = $description;
    }
    public function getDescription() {
        return $this->description;
    }

    public function setPublished($published) {
        $this->published = $published;
    }

    public function setModified($modified) {
        $this->modified = $modified;
    }
    public function setTitle($title) {
        $this->title = $title;
    }
    public function getTitle() {
        return $this->title;
    }
    public function render($name) {
        $this->placeholder = $name;
        require 'application/'.strtolower(Venus::$module).'/view/layout/'.$this->layout.'.php';
    }
    public function placeholder() {
        $names = explode('/', $this->placeholder);
        if(count($names) == 1) {
            $this->placeholder = strtolower(Venus::$controller) .'/'. $this->placeholder;
        }
        require 'application/'.strtolower(Venus::$module).'/view/'.$this->placeholder.'.php';
    }
}