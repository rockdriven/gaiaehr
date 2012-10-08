<?php
/**
 * Created by JetBrains PhpStorm.
 * User: erodriguez
 * Date: 4/14/12
 * Time: 12:24 PM
 * To change this template use File | Settings | File Templates.
 */
if(!isset($_SESSION)) {
    session_name('GaiaEHR');
    session_start();
    session_cache_limiter('private');
}
include_once('Reports.php');
include_once($_SESSION['site']['root'] . '/classes/dbHelper.php');
include_once($_SESSION['site']['root'] . '/dataProvider/Patient.php');
include_once($_SESSION['site']['root'] . '/dataProvider/User.php');
include_once($_SESSION['site']['root'] . '/dataProvider/Fees.php');
include_once($_SESSION['site']['root'] . '/dataProvider/i18nRouter.php');


class SuperBill extends Reports
{
    private $db;
    private $user;
    private $patient;
    private $fees;
    function __construct()
    {
	    parent::__construct();
        $this->db       = new dbHelper();
        $this->user     = new User();
        $this->patient  = new Patient();
        $this->fees     = new Fees();
        return;
    }

    public function CreateSuperBill(stdClass $params){
        $html = '';
        foreach($this->getEncounterByDateFromToAndPatient($params->from,$params->to,$params->pid) AS $eData) {
            $html .= $this->htmlSuperBill($eData);
        }
        ob_end_clean();
	    $Url = $this->ReportBuilder($html);
        return array('success' => true, 'html' => $html, 'url' => $Url);
    }

    public function getEncounterByDateFromToAndPatient($from,$to,$pid = null)
    {
	    $sql = 'SELECT form_data_encounter.pid,
                       form_data_encounter.eid,
                       form_data_encounter.start_date,
                       form_data_demographics.*
               	  FROM form_data_encounter
             LEFT JOIN form_data_demographics ON form_data_encounter.pid = form_data_demographics.pid';
	    if($from != '' && $to != '') $sql .= "WHERE form_data_encounter.start_date BETWEEN '$from' AND '$to'";
	    if($pid != '') $sql .= " AND form_data_encounter.pid = '$pid'";
        $this->db->setSQL($sql);
        return $this->db->fetchRecords(PDO::FETCH_ASSOC);
    }

    public function htmlSuperBill($params){
        $html = '';
        $html .=
            "<table border=\"0\" width=\"100%\" >
                 <tr>
                    <th colspan=\"6\">".i18nRouter::t("patient_data")."</th>
                 </tr>
                 <tr>
                    <td>".i18nRouter::t("title")."</td>
                    <td>".i18nRouter::t("first_name")."</td>
                    <td>".i18nRouter::t("middle_name")."</td>
                    <td>".i18nRouter::t("last_name")."</td>
                    <td>".i18nRouter::t("sex")."</td>
                    <td>".i18nRouter::t("ss")."</td>
                 </tr>
                 <tr>
                    <td>".$params['title']."</td>
                    <td>".$params['fname']."</td>
                    <td>".$params['mname']."</td>
                    <td>".$params['lname']."</td>
                    <td>".$params['sex']."</td>
                    <td>".$params['SS']."</td>
                 </tr>
                 <tr>
                    <td>".i18nRouter::t("date_of_birth")."</td>
                    <td>".i18nRouter::t("street")."</td>
                    <td>".i18nRouter::t("city")."</td>
                    <td>".i18nRouter::t("state")."</td>
                    <td>".i18nRouter::t("zip")."</td>
                    <td>".i18nRouter::t("country")."</td>
                 </tr>
                 <tr>
                    <td>".$params['DOB']."</td>
                    <td>".$params['address']."</td>
                    <td>".$params['city']."</td>
                    <td>".$params['state']."</td>
                    <td>".$params['zipcode']."</td>
                    <td>".$params['country']."</td>
                 </tr>
                 <tr>
                    <td>".i18nRouter::t("occupation")."</td>
                    <td>".i18nRouter::t("home_phone")."</td>
                    <td>".i18nRouter::t("mobile_phone")."</td>
                    <td>".i18nRouter::t("emer_phone")."</td>
                    <td>".i18nRouter::t("emer_contact")."</td>
                    <td>".i18nRouter::t("allow_email")."</td>
                 </tr>
                 <tr>
                    <td>".$params['occupation']."</td>
                    <td>".$params['home_phone']."</td>
                    <td>".$params['mobile_phone']."</td>
                    <td>".$params['lname']."</td>
                    <td>".$params['sex']."</td>
                    <td>".$params['SS']."</td>
                 </tr>
                 <tr>
                    <td colspan=\"2\">".i18nRouter::t("allow_voice_message")."</td>
                    <td colspan=\"2\">".i18nRouter::t("allow_mail_message")."</td>
                    <td colspan=\"2\">".i18nRouter::t("allow_leave_message")."</td>
                 </tr>
                <tr>
                    <td colspan=\"2\">".$params['allow_voice_msg']."</td>
                    <td colspan=\"2\">".$params['allow_mail_msg']."</td>
                    <td colspan=\"2\">".$params['allow_leave_msg']."</td>
                 </tr>".
                '</table>'
        ;
        // INSURANCE DATA _~_~_~_~_~_~__~~
        $html .=
            "<table  border=\"0\" width=\"100%\">
                 <tr>
                    <th colspan=\"6\">".i18nRouter::t("insurance_data")." (".i18nRouter::t("primary").")</th>
                 </tr>
                 <tr>
                    <td>".i18nRouter::t("provider")."</td>
                    <td>".i18nRouter::t("plan_name")."</td>
                    <td>".i18nRouter::t("policy_number")."</td>
                    <td>".i18nRouter::t("group_number")."</td>
                    <td>".i18nRouter::t("subscriber_first_name")."</td>
                    <td>".i18nRouter::t("subscriber_middle_name")."</td>
                 </tr>
                 <tr>
                    <td>".$params['primary_insurance_provider']."</td>
                    <td>".$params['primary_plan_name']."</td>
                    <td>".$params['primary_policy_number']."</td>
                    <td>".$params['primary_group_number']."</td>
                    <td>".$params['primary_subscriber_fname']."</td>
                    <td>".$params['primary_subscriber_mname']."</td>
                 </tr>
                 <tr>
                    <td>".i18nRouter::t("subscriber_last_name")."</td>
                    <td>".i18nRouter::t("subscriber_relationship")."</td>
                    <td>".i18nRouter::t("subscriber_ss")."</td>
                    <td>".i18nRouter::t("subscriber_date_of_birth")."</td>
                    <td>".i18nRouter::t("subscriber_phone")."</td>
                    <td>".i18nRouter::t("subscriber_address")."</td>
                 </tr>
                 <tr>
                    <td>".$params['primary_subscriber_lname']."</td>
                    <td>".$params['primary_subscriber_relationship']."</td>
                    <td>".$params['city']."</td>
                    <td>".$params['state']."</td>
                    <td>".$params['primary_subscriber_phone']."</td>
                    <td>".$params['primary_subscriber_street']."</td>
                 </tr>
                 <tr>
                    <td>".i18nRouter::t("subscriber_zip")."</td>
                    <td>".i18nRouter::t("subscriber_city")."</td>
                    <td>".i18nRouter::t("subscriber_state")."</td>
                    <td>".i18nRouter::t("subscriber_country")."</td>
                    <td>".i18nRouter::t("subscriber_employer")."</td>
                    <td>".i18nRouter::t("subscriber_employer_street")."</td>
                 </tr>
                 <tr>
                    <td>".$params['primary_subscriber_zip_code']."</td>
                    <td>".$params['primary_subscriber_city']."</td>
                    <td>".$params['primary_subscriber_state']."</td>
                    <td>".$params['primary_subscriber_country']."</td>
                    <td>".$params['primary_subscriber_employer']."</td>
                    <td>".$params['primary_subscriber_employer_city']."</td>
                 </tr>
                 <tr>
                    <td>".i18nRouter::t("subscriber_employer_city")."</td>
                    <td>".i18nRouter::t("subscriber_employer_zip")."</td>
                    <td>".i18nRouter::t("subscriber_employer_state")."</td>
                    <td colspan=\"3\">".i18nRouter::t("subscriber_employer_country")."</td>
                 </tr>
                 <tr>
                    <td>".$params['primary_subscriber_employer_city']."</td>
                    <td>".$params['primary_subscriber_employer_zip_code']."</td>
                    <td>".$params['primary_subscriber_employer_state']."</td>
                    <td colspan=\"3\">".$params['primary_subscriber_employer_country']."</td>
                 </tr>";
        if(isset($params['secondary_insurance_provider'])){
            $html .=
                "<tr>
                    <th colspan=\"6\">".i18nRouter::t("secondary")."</th>
                </tr>
                <tr>
                    <td>".i18nRouter::t("provider")."</td>
                    <td>".i18nRouter::t("plan_name")."</td>
                    <td>".i18nRouter::t("policy_number")."</td>
                    <td>".i18nRouter::t("group_number")."</td>
                    <td>".i18nRouter::t("subscriber_first_name")."</td>
                    <td>".i18nRouter::t("subscriber_middle_name")."</td>
                </tr>
                <tr>
                    <td>".$params['secondary_insurance_provider']."</td>
                    <td>".$params['secondary_plan_name']."</td>
                    <td>".$params['secondary_policy_number']."</td>
                    <td>".$params['secondary_group_number']."</td>
                    <td>".$params['secondary_subscriber_fname']."</td>
                    <td>".$params['secondary_subscriber_mname']."</td>
                </tr>
                <tr>
                    <td>".i18nRouter::t("subscriber_last_name")."</td>
                    <td>".i18nRouter::t("subscriber_relationship")."</td>
                    <td>".i18nRouter::t("subscriber_ss")."</td>
                    <td>".i18nRouter::t("subscriber_date_of_birth")."</td>
                    <td>".i18nRouter::t("subscriber_phone")."</td>
                    <td>".i18nRouter::t("subscriber_address")."</td>
                </tr>
                <tr>
                    <td>".$params['secondary_subscriber_lname']."</td>
                    <td>".$params['secondary_subscriber_relationship']."</td>
                    <td>".$params['secondary_subscriber_city']."</td>
                    <td>".$params['secondary_subscriber_state']."</td>
                    <td>".$params['secondary_subscriber_phone']."</td>
                    <td>".$params['secondary_subscriber_street']."</td>
                </tr>
                <tr>
                    <td>".i18nRouter::t("subscriber_zip")."</td>
                    <td>".i18nRouter::t("subscriber_city")."</td>
                    <td>".i18nRouter::t("subscriber_state")."</td>
                    <td>".i18nRouter::t("subscriber_country")."</td>
                    <td>".i18nRouter::t("subscriber_employer")."</td>
                    <td>".i18nRouter::t("subscriber_employer_street")."</td>
                </tr>
                <tr>
                    <td>".$params['secondary_subscriber_zip_code']."</td>
                    <td>".$params['secondary_subscriber_city']."</td>
                    <td>".$params['secondary_subscriber_state']."</td>
                    <td>".$params['secondary_subscriber_country']."</td>
                    <td>".$params['secondary_subscriber_employer']."</td>
                    <td>".$params['secondary_subscriber_employer_city']."</td>
                </tr>
                <tr>
                    <td colspan=\"2\">>".i18nRouter::t("subscriber_employer_city")."</td>
                    <td colspan=\"2\">>".i18nRouter::t("subscriber_employer_zip")."</td>
                    <td colspan=\"2\">>".i18nRouter::t("subscriber_employer_state")."</td>
                    <td colspan=\"2\">>".i18nRouter::t("subscriber_employer_country")."</td>
                </tr>
                <tr>
                    <td>".$params['secondary_subscriber_employer_city']."</td>
                    <td>".$params['secondary_subscriber_employer_zip_code']."</td>
                    <td>".$params['secondary_subscriber_employer_state']."</td>
                    <td>".$params['secondary_subscriber_employer_country']."</td>
                </tr>"
            ;
        }
        if(isset($params['tertiary_insurance_provider'])){
            $html .=
                "<tr>
                    <th>".i18nRouter::t("tertiary")."</th>
                 </tr>
                 <tr>
                    <td>".i18nRouter::t("provider")."</td>
                    <td>".i18nRouter::t("plan_name")."</td>
                    <td>".i18nRouter::t("policy_number")."</td>
                    <td>".i18nRouter::t("group_number")."</td>
                    <td>".i18nRouter::t("subscriber_first_name")."</td>
                    <td>".i18nRouter::t("subscriber_middle_name")."</td>
                 </tr>
                 <tr>
                    <td>".$params['tertiary_insurance_provider']."</td>
                    <td>".$params['tertiary_plan_name']."</td>
                    <td>".$params['tertiary_policy_number']."</td>
                    <td>".$params['tertiary_group_number']."</td>
                    <td>".$params['tertiary_subscriber_fname']."</td>
                    <td>".$params['tertiary_subscriber_mname']."</td>
                 </tr>
                 <tr>
                    <td>".i18nRouter::t("subscriber_last_name")."</td>
                    <td>".i18nRouter::t("subscriber_relationship")."</td>
                    <td>".i18nRouter::t("subscriber_ss")."</td>
                    <td>".i18nRouter::t("subscriber_date_of_birth")."</td>
                    <td>".i18nRouter::t("subscriber_phone")."</td>
                    <td>".i18nRouter::t("subscriber_address")."</td>
                 </tr>
                 <tr>
                    <td>".$params['tertiary_subscriber_lname']."</td>
                    <td>".$params['tertiary_subscriber_relationship']."</td>
                    <td>".$params['tertiary_subscriber_city']."</td>
                    <td>".$params['tertiary_subscriber_state']."</td>
                    <td>".$params['tertiary_subscriber_phone']."</td>
                    <td>".$params['tertiary_subscriber_street']."</td>
                 </tr>
                 <tr>
                    <td>".i18nRouter::t("subscriber_zip")."</td>
                    <td>".i18nRouter::t("subscriber_city")."</td>
                    <td>".i18nRouter::t("subscriber_state")."</td>
                    <td>".i18nRouter::t("subscriber_country")."</td>
                    <td>".i18nRouter::t("subscriber_employer")."</td>
                    <td>".i18nRouter::t("subscriber_employer_street")."</td>
                 </tr>
                 <tr>
                    <td>".$params['tertiary_subscriber_zip_code']."</td>
                    <td>".$params['tertiary_subscriber_city']."</td>
                    <td>".$params['tertiary_subscriber_state']."</td>
                    <td>".$params['tertiary_subscriber_country']."</td>
                    <td>".$params['tertiary_subscriber_employer']."</td>
                    <td>".$params['tertiary_subscriber_employer_city']."</td>
                 </tr>
                 <tr>
                    <td colspan=\"2\">>".i18nRouter::t("subscriber_employer_city")."</td>
                    <td colspan=\"2\">>".i18nRouter::t("subscriber_employer_zip")."</td>
                    <td colspan=\"2\">>".i18nRouter::t("subscriber_employer_state")."</td>
                    <td colspan=\"2\">>".i18nRouter::t("subscriber_employer_country")."</td>
                 </tr>
                 <tr>
                    <td>".$params['tertiary_subscriber_employer_city']."</td>
                    <td>".$params['tertiary_subscriber_employer_zip_code']."</td>
                    <td>".$params['tertiary_subscriber_employer_state']."</td>
                    <td>".$params['tertiary_subscriber_employer_country']."</td>
                 </tr>";
        }
	    $html .="</table>";
        $html .=
	        "<table border=\"0\" width=\"100%\">
	         <tr>
	            <th>".i18nRouter::t("billing_information")."</th>
	         </tr>
	         <tr>
	            <td>".i18nRouter::t("date")."</td>
	            <td>".i18nRouter::t("provider")."</td>
	            <td>".i18nRouter::t("code")."</td>
	            <td>".i18nRouter::t("fee")."</td>
	         </tr>
	         <tr>
	            <td>".$params['date']."</td>
	            <td>".$params['provider']."</td>
	            <td>".$params['code']."</td>
	            <td>".$params['fee']."</td>
	         </tr>

	         </table>

	    <hr>
		";
        return $html;
    }
}
//$e = new SuperBill();
//$params = new stdClass();
//$params->pid = 1;
//$params->from = '2011-09-05';
//$params->to = '2013-09-05';
//echo '<pre>';
//print_r($e->CreateSuperBill($params));