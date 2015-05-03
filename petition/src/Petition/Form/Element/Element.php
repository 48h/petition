<?php
/**
 * Created by PhpStorm.
 * User: fonpah
 * Date: 01.05.2015
 * Time: 21:23
 */
namespace Petition\Form\Element;
class Element {
    /*
     * position of either the label icon or input addon
     */
    const POSITION_BEFORE ='before';
    const POSITION_AFTER = 'after';

    protected $id;
    protected $type = 'text';
    protected $value;
    protected $name;
    protected $labelText;
    protected $helpText_html;
    protected $helpText;
    protected $isRequired = false;
    protected $cssClasses = array('input-xlarge' => 'input-xlarge');
    protected $attr = array();
    protected $hasControlGroup = true;
    protected $validators = array();
    protected $labelIcon = null;
    protected $inputAddon = null;
    protected $isInline =false;
    private $errorMessage = 'invalid_value';
    protected $selectOpts = array();
    protected $data = array();
    protected $error = array();
    public function __construct($name, $data= array()) {

        if(isset($data[$name])){
            $this->setValue($data[$name]);
        }
        $this->name = $name;
        $this->id = 'nz-bt-' . $name;
        $this->data = $data;

        return $this;
    }

    public function setId($id){
        $this->id = $id;
        return $this;
    }

    public function getId(){
        return $this->id;
    }

    public function setName($name){
        $this->name = $name;
        return $this;
    }
    public function getName(){
        return $this->name;
    }

    public function setValue($value){
        $this->value = $value;
        return $this;
    }

    public function getValue(){
        return $this;
    }
    public function setAttribute($key, $value){
        $this->attr[$key] = $value;
        return $this;
    }

    public function getAttribute($key){
        if(isset($this->attr[$key])){
            return $this->attr[$key];
        }
        return false;
    }

    public function setInline($boolean= false){
        $this->isInline = $boolean;
        return $this;
    }

    public function setHasControlGroup($boolean = true){
        $this->hasControlGroup = $boolean;
        return $this;
    }
    public function setType($type){
        $this->type = $type;
        return $this;
    }
    public function setRequired($bool=true){
        $this->isRequired = $bool;
        return $this;
    }
    public function addClass($cssClass) {
        $this->cssClasses[$cssClass] = $cssClass;
        return $this;
    }

    public function setCssClass($cssClass) {
        $this->cssClasses = array($cssClass => $cssClass);
        return $this;
    }
    public function setInputAddon($position = Element::POSITION_AFTER, $html){
        if($html){
            $obj = new \stdClass();
            $obj->position = ($position == self::POSITION_BEFORE? $position: self::POSITION_AFTER);
            $obj->html =  '<div class="input-group-addon">'.$html.'</div> ';
            $this->inputAddon = $obj;
        }
        return $this;
    }
    public function setLabelIcon($position = Element::POSITION_BEFORE, $iconClass){
        if($iconClass){
            $obj = new \stdClass();
            $obj->position = ($position == self::POSITION_AFTER? $position: self::POSITION_BEFORE);
            $obj->html =  '<i class="'.$iconClass.'"></i> ';
            $this->labelIcon = $obj;
        }
        return $this;
    }
    public function addValidator(ValidatorInterface $validator){
        $this->validators[] = $validator;
        return $this;
    }
    public function toHTML_V3($col_1 = 'col-lg-2', $col_2 = 'col-lg-5') {

        $html = '';
        $html .= $this->renderFormGroup($col_1,$col_2);
        return $html;
    }

    public function renderLabel($col_1 = 'col-lg-2'){
        $requiredHTML = '';
        if ($this->isRequired) {
            $requiredHTML = '<sup style="color: #ff0000;">*</sup>';
        }

        $html = '<label class="control-label '.($this->isInline?'':$col_1).'" for="' . $this->name . '">' ;
        if(isset($this->labelIcon) && $this->labelIcon->position == self::POSITION_BEFORE){
            $html .= $this->labelIcon->html;
        }
        $html .= $this->labelText ;
        if(isset($this->labelIcon) && $this->labelIcon->position == self::POSITION_AFTER){
            $html .= $this->labelIcon->html;
        }
        $html .= $requiredHTML ;
        $html .= '</label>';
        return $html;
    }

    public function renderFormGroup($col_1 = 'col-lg-2', $col_2 = 'col-lg-5'){
        $html = '';

        $errorClass = '';
        if ($this->hasError()) {
            $errorClass = ' has-error';
        };

        $html .= ' <div class="form-group' . $errorClass . '">
            ';
        if (isset($this->labelText)) {
            $html .= $this->renderLabel($col_1);
        }
        $html .= $this->renderInputControl($col_2);
        $html .= '
            </div>
            ';
        return $html;
    }
    public function renderInputControl($col_2 = 'col-lg-5'){
        $html = '';
        if(!$this->isInline){
            $html .= '<div class="'.$col_2.'">';
        }

        $html .= $this->renderInput();

        $html .= $this->renderHelpText();
        if(!$this->isInline){
            $html .= '</div>';
        }
        return $html;

    }
    public function renderHelpText(){
        $helpText = $this->helpText_html;
        $html = '';
        if ($msg = $this->getErrorMessage($this->name)) {
            $helpText = $msg;
        }
        if ($helpText && $this->hasControlGroup) {
            $html .= '<span class="help-block">' . $helpText . '</span>';
        }
        return $html;
    }
    public function getErrorMessage($name){
        if(isset($this->error[$name])){
            return $this->error[$name];
        }
        return null;
    }

    public function toTexArea() {
        $this->type = 'textarea';
        return $this;
    }

    public function toCheckbox() {
        $this->type = 'checkbox';
        return $this;
    }

    public function toSelect($opts = array()) {
        $this->selectOpts = $opts;
        $this->type = 'select';
        return $this;
    }
    public function toRadio($opts = array()) {
        $this->selectOpts = $opts;
        $this->type = 'radio';
        return $this;
    }

    public function hasError() {
        if ($this->getErrorMessage($this->name)) {
            return TRUE;
        }
        return FALSE;
    }

    public function renderInput() {
        $this->addClass('form-control');
        $requiredAttr = '';
        if ($this->isRequired) {
            $requiredAttr = ' required="true"';
        }

        foreach ($this->attr as $k => $v) {
            $requiredAttr .= ' ' . $k . '="' . $v . '"';
        }

        if ($this->type == 'select') {
            $cssClass_html = '';
            $cssClasses = $this->cssClasses;
            unset( $cssClasses['input-xlarge'] );
            if( $cssClasses ){
                $cssClass_html = 'class="' . implode(' ', $cssClasses) . '"';
            }

            return '<select' . $requiredAttr . ' name="' . $this->name . '" id="' . $this->id . '"'.$cssClass_html.'>' . $this->options($this->selectOpts, $this->value) . '</select>';
        }

        if ($this->type == 'radio') {
            $val = isset($this->data[$this->name]) ?$this->data[$this->name] : '';
            $radioBoxes =  $this->radioBoxes($this->name, $this->selectOpts, $val, $attr = '');
            $h = '';
            foreach( $radioBoxes as $text => $input ){
                $h .= '<div class="radio"><label >'.$input.''.$text.'</label></div>';
            }
            return $h;
        }

        if ($this->type == 'checkbox') {
            $checked = ' checked';
            if (isset($this->data[$this->name])) {
                if ($this->data[$this->name] != 1) {
                    $checked = '';
                }
            }
            return '<div class="checkbox">'
                        .'<label>'
                            .'<input type="checkbox" name="' . $this->name . '"' . $checked . ' value="1">'
                        .'</label>'
                    .'</div>';
        }
        if ($this->type == 'textarea') {
            if( !$rows = $this->getAttribute('rows') ){
                $rows = 5;
            }
            if( !$cols = $this->getAttribute('cols') ){
                $cols = 8;
            }

            return '<textarea' . $requiredAttr . ' cols="'.$cols.'"  rows="'.$rows.'" name="' . $this->name . '" class="' . implode(' ', $this->cssClasses) . '">' . esc_textarea($this->value) . '</textarea>';
        }


        $input =  '<input' . $requiredAttr . ' type="' . $this->type . '" name="' . $this->name . '" value="' . esc_html($this->value) . '" id="' . $this->id . '" class="' . implode(' ', $this->cssClasses) . '">';
        if(isset($this->inputAddon) ){

            if($this->inputAddon->position == self::POSITION_BEFORE){
                $input = '<div class="input-group">'.$this->inputAddon->html.$input.'</div>';
            }
            elseif($this->inputAddon->position == self::POSITION_AFTER){
                $input = '<div class="input-group">'.$input.$this->inputAddon->html.'</div>';
            }
        }
        return $input;
    }
    public function isRequired() {
        if ($this->getAttribute('disabled')) {
            return FALSE;
        }
        return $this->isRequired;
    }
    public function validate() {
        //TODO
        return true;
    }

    public function setLabelText($text) {
        $this->labelText = $text;
        return $this;
    }

    public function getLabelText() {
        return $this->labelText;
    }

    public function options($opts, $sel = null) {

        $r = '';
        foreach ($opts as $k => $v) {


            if ($k == $sel) {
                $r .= '<option value="' . $k . '" selected="selected">' . esc_html($v) . '</option>';
            } else {
                $r .= '<option value="' . $k . '">' . esc_html($v) . '</option>';
            }
        }
        return $r;
    }

    public function optionsExt($opts, $sel = null) {

        $r = '';
        foreach ($opts as $k => $data) {

            if (isset($data["onClick"]))
                $oncl = ' onclick="' . $data["onClick"] . '" ';

            if ($k == $sel) {
                $r .= '<option ' . $oncl . ' value="' . $k . '" selected="selected">' . esc_html($data["v"]) . '</option>';
            } else {
                $r .= '<option ' . $oncl . ' value="' . $k . '">' . esc_html($data["v"]) . '</option>';
            }
        }
        return $r;
    }

    public function checkboxes($name, array $otps, $sel, $attr = '') {
        $r = array();
        $selKeys = array_flip((array) $sel);
        foreach ($otps as $k => $v) {

            if (isset($selKeys[$k])) {
                $r[$v] = ' <input ' . $attr . ' name="' . $name . '" type="checkbox" checked="checked" value="' . esc_html($k) . '">';
            } else {
                $r[$v] = ' <input ' . $attr . ' name="' . $name . '" type="checkbox" value="' . esc_html($k) . '">';
            }
        }
        return $r;
    }

    public function radioBoxes($name, array $otps, $sel, $attr) {
        $r = array();

        foreach ($otps as $k => $v) {

            if ($k == $sel) {
                $r[$v] = ' <input ' . $attr . ' name="' . $name . '" type="radio" checked="checked" value="' . esc_html($k) . '">';
            } else {
                $r[$v] = ' <input ' . $attr . ' name="' . $name . '" type="radio" value="' . esc_html($k) . '">';
            }
        }
        return $r;
    }

}