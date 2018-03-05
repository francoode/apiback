<?php
/**
 * Created by PhpStorm.
 * User: franco
 * Date: 29/01/18
 * Time: 13:02
 */
namespace AppBundle\Util;

class AutomaticLogMessagesConst
{

    //Connst Entity names

    CONST LogActionBusiness = 'AppBundle\Entity\LogActionBusiness';
    CONST NoteBusiness = 'AppBundle\Entity\NoteBusiness';

    CONST LogActionContact = 'AppBundle\Entity\LogActionContact';
    CONST NoteContact = 'AppBundle\Entity\NoteContact';

    CONST LogActionCompany = 'AppBundle\Entity\LogActionCompany';
    CONST NoteCompany = 'AppBundle\Entity\NoteCompany';

    CONST BusinessEntity = 'AppBundle\Entity\Business';
    CONST CompanyEntity = 'AppBundle\Entity\Company';
    CONST ContactEntity = 'AppBundle\Entity\Contact';



    //Const type of Event
    CONST type_create = "postPersist";
    CONST type_update = "postUpdate";
    CONST type_remove = "postRemove";

    //Const White Space
    CONST whiteSpace = " ";
    CONST whiteSpacecoma = " ,";
    CONST comaWhiteSpace = ", ";

    //Const create message
    CONST update = ' ha modificado:';
    CONST create = ' ha creado';
    CONST delete = ' ha eliminado';

    //Const articles
    CONST la = ' la';
    CONST el = ' el';
    CONST un = ' un';
    CONST una = ' una';
    CONST a = ' a';
    CONST en = ' en';


    CONST business = ' negocio ';
    CONST contact = ' contacto ';
    CONST company = ' empresa ';
    CONST note = ' nota ';
    CONST task = ' tarea';


    //Const variables translations
    CONST varTranslations = array(
        'name' => ' nombre',
        'closingDate' => ' fecha de cierre',
        'amount' => ' monto',
        'state' => ' estado',
        'description' => ' descripción',
    );


}