
<?php
class UserController extends Controller {

	public $_user;

	public function filters() {
		return array(
			'checkAndSetUser + delete, updateEmail, updatePassword, profileById, profile, login',
			'recoverAccount + recover',
			);
	}

	public function filterCheckAndSetUser($filterChain) {
		if(!$_GET['id'])
			$this->renderError("Invalid Data!");
		else {
			$this->_user = User::model()->active()->findByPk($_GET['id']);
			if(!$this->_user)
				$this->renderError("Invalid Data!");			
		}
		$filterChain->run();
	}

	public function filterRecoverAccount($filterChain){

		if(!$_GET['id'])
			$this->renderError("Invalid Data!");
		else {

			$this->_user = User::model()->deactivated()->findByPk($_GET['id']);
			if(!$this->_user)
				$this->renderError("Invalid Data!");			
		}
		$filterChain->run();
	}

	public function actionCreate() {
		if(isset($_POST['new_user'])) {
			$new_user = User::create($_POST['new_user']);
			if(!$new_user->errors) {
				$this->renderSuccess(array('user_id'=>$new_user->id,'name'=>$new_user->name,'password'=>$new_user->password,'email'=>$new_user->email));
			} else {
				$this->renderError($this->getErrorMessageFromModelErrors($new_user));
			}
		} else {
			$this->renderError('Please send User data proplerly !');
		}
	}

	public function actionViewNewsfeed($id) {												//view all posts
		$posts = Post::model()->findAllByAttributes(array('user_id'=>$id));
		$posts_data = array();
		foreach ($posts as $post) {
			$posts_data[] = array('id'=>$post->id, 'content'=>$post->content);
		}
		echo CJSON::encode(array('status'=>'SUCCESS',
			'posts_data'=>$posts_data));
	}

	/*public function actionProfile($id) {                //user profile by id
		$user = User::model()->findByPk($id);
		if(!$user) {
			$this->renderPrint('Account does not exist.');
		}
		else {
			echo CJSON::encode(array('status'=>'SUCCESS', 'name'=>$user->name, 'email'=>$user->email));
		} 
	}

	public function actionLogin($id) {
		$user = User::model()->findByPk($id);
		if(!$user) {
			$this->renderPrint('Account does not exist.');
		}
		else {
			echo CJSON::encode(array('status'=>'SUCCESS', 'id'=>$user->id));
		}
	}
*/

	public function actionSearchProfile($name){
		$users = User::model()->findAllByAttributes(array('name'=>$name));
		if(!$users) {
			$this->renderPrint('Account does not exist.');
		}
		else{
			$users_profile = array();
			foreach($users as $user){
				$users_profile[] = array('user_id'=>$user->id, 'user_name'=>$user->name, 'email'=>$user->email);
			}	
		}
		echo CJSON::encode(array('status'=>'SUCCESS', 'users_profile'=>$users_profile));
	}
	public function actionProfileById($id) {   
		$users_profile = array();
		//$this->renderSuccess(
			$users_profile[] =array('name'=>$this->_user->name, 'email'=>$this->_user->email);
		echo CJSON::encode(array('status'=>'SUCCESS', 'users_profile'=>$users_profile));
	}

	public function actionDelete($id){                 
		$this->_user->deactivate($id);
		$this->renderPrint(array('message'=>'Post Deleted Successfully'));
	}

	public function actionRecover($id){                  
		$this->_user->activate($id);
		$this->renderPrint(array('message'=>'Post Activated Successfully'));
	}

	public function actionUpdateEmail($emailid, $id){
		//$users = User::model()->findByPk($id);
		if($this->_user->status == Comment::STATUS_ACTIVE){
			$this->_user->email = $emailid;	
			$this->_user-ssave();
			$this->renderPrint('Email ID Updated');	
		}
	}
	public function actionUpdatePassword($password, $id){
		//$users = User::model()->findByPk($id);
		if($this->_user->status == Comment::STATUS_ACTIVE){
			$this->_user->password = $password;
			$this->_user->save();
			$this->renderPrint('Password Updated');	
		}
	}
}