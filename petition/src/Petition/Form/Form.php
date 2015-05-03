<?php
/**
 * Created by PhpStorm.
 * User: fonpah
 * Date: 01.05.2015
 * Time: 21:22
 */

namespace Petition\Form;
use Petition\Form\Element\Element;
class Form {
    protected $data = array();
    protected $actionURL;
    protected $submitClasses = array();
    protected $elements = array();
    protected $hiddenElements = array();

    const METHOD_POST = 'post';
    const METHOD_GET = 'get';
    const FORM_HORIZONTAL ='form-horizontal';

    protected $method = 'post';
    protected $submitValue = 'send';
    protected $cssClasses = array();
    protected $hasFormActions = true;
    protected $hasWell = FALSE;
    protected $id;
    protected $name;
    protected $encType;
    protected $onsubmit;
    protected $legend;

    function __construct( $data = array()) {
        $this->data = $data;
        $this->id = 'bform-id-' . time();
        $this->name = 'bform';
    }

    public function setID($id) {
        $this->id = $id;
        return $this;
    }

    public function getID() {
        return $this->id;
    }

    public function setAction($url) {
        $this->actionURL = $url;
        return $this;
    }

    public function getAction() {
        return $this->actionURL;
    }

    public function setMethod($method) {
        $this->method = $method;
    }

    public function setCssClass($class) {
        $this->cssClasses = array($class);
    }

    public function addCssClass($class) {
        $this->cssClasses[] = $class;
    }

    public function setLegend($legend) {
        $this->legend = $legend;
    }

    public function setHasFormActions($hasFormActions) {
        $this->hasFormActions = $hasFormActions;
    }

    public function setHasWell($hasWell) {
        $this->hasWell = $hasWell;
    }

    public function setEncType($encType) {
        $this->encType = $encType;
    }

    public function getEncType() {
        return $this->getEncType;
    }

    public function getName() {
        return $this->name;
    }
    public function setOnSubmit( $event ){
        $this->onsubmit = $event;
    }
    public function getOnSubmit(){
        return $this->onsubmit;
    }

    public function createElement($name) {
        $el = new Element($name, $this->data);
        $this->elements[$name] = $el;
        return $el;
    }

    public function addElement(Element $el ) {
        $this->elements[$el->getName()] = $el;
        return $el;
    }

    public function createHiddenElement($name, $value, $id = '') {
        $el = '<input type="hidden" name="' . $name . '" value="' . $value . '" id="' . $id . '"/>';
        $this->hiddenEelements[$name] = $el;
        return $el;
    }

    public function createCSRFElement($name){
        return wp_nonce_field($name);
    }
    public function getElements()
    {
        return $this->elements;
    }
    public function getElement( $name ) {
        if( isset($this->elements[$name]) ){
            return $this->elements[$name];
        }
        return NULL;
    }
    public function getHiddenElements() {
        return $this->hiddenEelements;
    }

    public function toHTML_V3($mainCol = 'col-lg-12', $col_1 = 'col-lg-2', $col_2 = 'col-lg-10') {

        $content = $this->openForm($mainCol);

        foreach ($this->elements as $el) {
            $content .= $el->toHTML_V3($col_1, $col_2);
        }

        foreach ($this->hiddenElements as $el) {
            $content .= $el;
        }
        $content .= $this->renderButton($col_1,$col_2);
        $content.= $this->closeForm();
        return $content;
    }

    public function toHTML() {
        $formClasses = implode(' ', $this->cssClasses);
        $classHTML = '';
        if ($formClasses) {
            $classHTML = ' class="' . $formClasses . '"';
        }

        $content = '<form action="' . $this->actionURL . '" target="_self" method="' . $this->method . '" id="' . $this->id . '"' . $classHTML . ' name="' . $this->name . '" ' . ($this->encType ? 'enctype="' . $this->encType . '"' : '') .' ' . ($this->onsubmit ? 'onsubmit="' . $this->onsubmit . '"' : '') .' >
                        <fieldset>';

        if (isset($this->legend)) {
            $content .= '<legend>' . $this->legend . '</legend>';
        }

        $wellClass = '';
        if ($this->hasWell) {
            $wellClass = 'well';
        };

        $content .= '       <div class="' . $wellClass . '" id="form-content">';

        foreach ($this->elements as $el) {
            $content .= $el->toHTML();
        }

        foreach ($this->hiddenEelements as $el) {
            $content .= $el;
        }

        if ($this->hasFormActions) {
            $content .= '</div>
                            <div class="form-actions">';
        }


        $submitClassesArray = $this->submitClasses;;
        if( !$submitClassesArray ){
            $submitClassesArray = array('btn btn-primary');
        }

        $submitClass = implode(' ', $submitClassesArray);
        if ($this->hasFormActions) {
            $submitClass .= ' offset3';
        };
        $content .= $this->view->submitInput($this->submitValue, ' class="' . $submitClass . '"');

        $content .= '</div>';

        $content .= '</fieldset>
                    </form>';
        return $content;
    }

    public function setSubmitValue($value) {
        $this->submitValue = $value;
    }

    public function addSubmitClass($class) {
        $this->submitClasses[] = $class;
    }

    public function removeElement($name) {
        unset($this->elements[$name]);
    }

    public function validate() {
        $ret = TRUE;
        foreach ($this->elements as $name => $el) {
            if ($el->getAttribute('disabled')) {
                if(isset($this->data[$name])){
                    unset($this->data[$name]);
                }
            }

            if ($el->isRequired()) {
                if (!isset($this->data[$name])) {
                    $this->error($name, 'This is a required field.');
                    $ret = FALSE;
                }
                elseif($el->getType() =='email'){

                }
            }

            if (!$el->validate()) {
                $ret = FALSE;
            }
        }

        return $ret;
    }
    public function openForm($mainCol = 'col-lg-12'){
        $formClasses = implode(' ', $this->cssClasses);
        $classHTML = '';
        if ($formClasses) {
            $classHTML = ' class="' . $formClasses . '"';
        }

        $content = '<div class="' . $mainCol . '"><form role="form" action="' . $this->actionURL . '" target="_self" method="' . $this->method . '" id="' . $this->id . '"' . $classHTML . ' name="' . $this->name . '" ' . ($this->encType ? 'enctype="' . $this->encType . '"' : '') . ' >
                        ';
        return $content;
    }
    public function closeForm(){
        $content = '
                    </form>';
        $content .= '</div>';
        return $content;
    }
    public function renderButton($col_1 = 'col-lg-2', $col_2 = 'col-lg-10'){
        $submitClass = implode(' ', $this->submitClasses);

        return '<div class="form-group"> <div class="'.$col_1.'"> </div> <div class="text-right '.$col_2.'">'.$this->submitInput($this->submitValue, ' class="btn btn-default' . $submitClass . '"').'</div></div>';
    }
    public function submitInput($value = '', $attr = '') {
        if (!$attr) {
            $attr = ' class="btn"';
        }

        return '<button type="submit" ' . $attr . '>' . $value . '</button>';
    }

}