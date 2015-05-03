<?php
/**
 * Created by PhpStorm.
 * User: fonpah
 * Date: 02.05.2015
 * Time: 01:43
 */

namespace Petition;


use Petition\Form\Form;

class Plugin
{

    public function __construct()
    {

        add_action('init',array($this, 'init'));
        add_shortcode('petition_form',array($this, 'addPetitionForm'));
    }

    public function loaded()
    {

    }

    public function init(){
    }

    public function addPetitionForm(){
        return  $this->buildPetitionForm(array());
    }

    public function buildPetitionForm(array $data){
        $form = new Form(array());
        $form->createElement('firstname')
            ->setRequired(true)
            ->setType('text')
            ->setLabelText('First Name');

        $form->addSubmitClass('btn-primary');
        $form->addCssClass('form-horizontal');
        $formHtml  = $form->toHTML_V3('col-md-12','col-md-3','col-md-9');
        return '<div class="row" >'.$formHtml.'</div>';
    }


}
