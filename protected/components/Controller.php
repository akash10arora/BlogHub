<?php
class Controller extends CController {

	public function getErrorMessageFromModelErrors($model, $implode_by='<br />') {
		$messages = array();
		foreach($model->errors as $error)
		$messages[] = $error[0];
		return implode($implode_by, $messages);
	}

	public function renderSuccess($data) {
		echo CJSON::encode(array_merge(array('status'=>'SUCCESS'), $data));
		exit();
	}

	public function renderError($error_message) {
		echo CJSON::encode(array('status'=>'ERROR', 'message'=>$error_message));
		exit();
	}
	public function renderDeactive($id) {
		echo CJSON::encode(array('message'=>'Account Deactivated', $id));
		exit();
	}
	
	public function renderPrint($print_message) {
		echo CJSON::encode(array('status'=>'SUCCESS', 'message'=>$print_message));
		exit();
	}

	
}
