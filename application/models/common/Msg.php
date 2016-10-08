<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Msg extends CI_Model
{
    //character/user mismatch
    const INVALID_REQUEST            = "Invalid request"; 
    //transaction or query error
    const DB_ERROR                   = "Unexpected failure connecting to database. Try again"; 
    
    const API_ALREADY_EXISTS         = "API key is already taken";
    const USER_ALREADY_EXISTS        = "Username is already taken";
    const USERNAME_TOO_SHORT         = "Username is too short (minimum 6 characters)";
    const INVALID_EMAIL              = "Invalid e-mail";
    const EMAIL_ALREADY_TAKEN        = "E-mail is already taken";
    const PASSWORDS_MISMATCH         = "Passwords are not identical";
    const PASSWORD_TOO_SHORT         = "Password too short (6 characters minimum)";
    
    const INVALID_API_MASK           = "Your provided API Key does not match the required permissions. \n Please use the generation link provided in the registration page";

    const INVALID_API_KEY            = "Invalid API Key";
    const INVALID_REPORT_SELECTION   = "Invalid report selection";
    const CHARACTER_ACCOUNT_MISMATCH = "One or more characters you selected do not belong to this account";
    const CHARACTER_ALREADY_TAKEN    = "One of more characters you selected already exist in the database";
    const NO_CHARACTER_SELECTED      = "Please select at least one character";
    const ACCOUNT_CREATE_SUCCESS     = "Account created successfully";
    
    const MISSING_INFO               = "Missing information provided";
    const INVALID_FORM               = "Invalid information provided";
    const REGION_NOT_FOUND           = "Unknown Region";

    const CITADELS_NOT_FOUND         = "Unable to load Citadel list";
    const TAX_SET_SUCCESS            = "Tax value set sucessfully";
    const TAX_SET_FAILURE            = "Unable to set tax value";
    const CITADEL_NOT_FOUND          = "Unknown citadel provided";
    const TAX_REMOVE_SUCCESS         = "Tax removed successfully";
    
    const INVALID_LOGIN              = "Invalid credentials";
    const LOGIN_SUCCESS              = "Login success";
    const LOGIN_NO_CHARS             = "It seems you have no characters in your account. Please insert a new API Key below";
    
    const XML_CONNECT_FAILURE        = "Unable to connect to XML API";
    const CREST_CONNECT_FAILURE      = "Unable to connect to CREST API";
    const CREST_TIMEOUT              = "Timeout of 10 seconds per request exceeded";
    
    const ITEM_NOT_FOUND             = "Unknown item provided";
    const ITEM_ADD_SUCCESS           = "Item added to Stock List sucessfully";
    const ITEM_MAX_REACHED           = "You have reached the maximum amount of 100 items per list";
    const ITEM_DELETE_SUCCESS        = "Item removed sucessfully";
    const ITEM_REMOVE_FAILURE        = "Unable to remove item. It may already be removed";
    const LIST_REMOVE_SUCCESS        = "Stock List removed sucessfully";
    const LIST_CREATE_SUCCESS        = "Stock List created successfully";
    const LIST_REMOVE_ERROR          = "Unable to remove Stock List. It may already be removed";
    const LIST_CREATE_ERROR          = "Unable to create Stock List";
    
    const ROUTE_CREATE_SUCCESS       = "Trade Route created successfully";
    const ROUTE_CREATE_ERROR         = "Unable to create Trade Route";
    const ROUTE_REMOVE_SUCCESS       = "Trade Route removed successfully";
    const ROUTE_REMOVE_ERROR         = "Unable to remove Trade Route. It may already be removed";
    const STATION_NOT_FOUND          = "Unknown Station provided";
    const ROUTE_ALREADY_EXISTS       = "This Trade Route already exists";
    
    const TRANSACTION_UNLINK_SUCCESS = "Transaction unlinked successfully";
    const TRANSACTION_UNLINK_ERROR   = "Unable to unlink Transaction. It may already be unlinked";
    const TRANSACTION_NOT_BELONG     = "This transaction does not belong to you";
    
    const EMAIL_SEND_FAILURE         = "Failed to send e-mail";
    const EMAIL_SEND_SUCCESS         = "E-mail sent to ";

    const EMAIL_CHANGE_SUCCESS       = "E-mail changed sucessfully";
    const REPORT_CHANGE_SUCCESS      = "Report selection changed successfully";
    const REPORT_CHANGE_ERROR        = "Unable to change report selection";

    const CHANGE_PASSWORD_SUCCESS    = "Sucessfully changed password";
    const CHANGE_PASSWORD_ERROR      = "Unable to change password";


    public function __construct()
    {
        parent::__construct();
        //log_message('error', get_object_vars($this));
    }

}
