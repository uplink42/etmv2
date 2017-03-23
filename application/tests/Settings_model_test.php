<?php 

class Settings_model_test extends CIUnit_Framework_TestCase
{   

    public function test_getEmail_regular()
    {
        $CI = &get_instance();
        $CI->load->model('Settings_model');
        $result   = $CI->Settings_model->getEmail(15);
        $expected = (object) ['email' => 'diamantinorgf@gmail.com'];
        $this->assertEquals($expected, $result);
    }


    public function test_getEmail_exception_no_user()
    {
        $this->setExpectedException('Exception'); 
        $CI = &get_instance();
        $CI->load->model('Settings_model');
        try {
            $CI->Settings_model->getEmail();
        } catch(Throwable $e) {
            $this->assertTrue(TRUE);
            return;
        }

        $this->assertTrue(FALSE);
    }


    public function test_getEmail_exception_invalid_user()
    {
        $this->setExpectedException('Exception'); 
        $CI = &get_instance();
        $CI->load->model('Settings_model');
        try {
            $CI->Settings_model->getEmail(999);
        } catch(Throwable $e) {
            $this->assertTrue(TRUE);
            return;
        }

        $this->assertTrue(FALSE);
    }


    public function test_getReportSelection()
    {
        $CI = &get_instance();
        $CI->load->model('Settings_model');
        $result   = $CI->Settings_model->getReportSelection(15);
        $expected = (object) ['reports' => 'none'];
        $this->assertEquals($expected, $result);
    }


    public function test_getReportSelection_no_user()
    {
        $this->setExpectedException('Exception'); 
        $CI = &get_instance();
        $CI->load->model('Settings_model');
        try {
            $CI->Settings_model->getReportSelection();
        } catch(Throwable $e) {
            $this->assertTrue(TRUE);
            return;
        }

        $this->assertTrue(FALSE);
    }


    public function test_getReportSelection_invalid_user()
    {
        $this->setExpectedException('Exception'); 
        $CI = &get_instance();
        $CI->load->model('Settings_model');
        try {
            $CI->Settings_model->getReportSelection(999);
        } catch(Throwable $e) {
            $this->assertTrue(TRUE);
            return;
        }

        $this->assertTrue(FALSE);
    }
}