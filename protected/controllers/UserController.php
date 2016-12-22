
<?php
class UserController extends Controller {

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

		public function actionView($id) {						//view post by id

			$view =  Post::model()->findByPk($id);
			if(!$view){
				$this->renderPrint('Account does not exist.');
			}   
			else{
				echo CJSON::encode(array('status'=>'SUCCESS','id'=>$view->id,'content'=>$view->content));   
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

	public function actionProfile($id) {                //user profile by id
		$user = User::model()->findbyPK($id);
		if(!$user) {
			$this->renderPrint('Account does not exist.');
		}
		else {
			echo CJSON::encode(array('status'=>'SUCCESS', 'name'=>$user->name, 'email'=>$user->email));
		} 
	}

	public function actionLogin($id) {
		$user = User::model()->findbyPK($id);
		if(!$user) {
			$this->renderPrint('Account does not exist.');
		}
		else {
			echo CJSON::encode(array('status'=>'SUCCESS', 'id'=>$user->id));
		}
	}


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

	public function actionDelete($id){                 
		$users = User::model()->findByPk($id);
		$users->status = Comment::STATUS_DEACTIVATED;
		$users->save();
		$this->renderPrint('Account Deactivated');
	}

	public function actionRecover($id){                  
		$users = User::model()->findByPk($id);	
		$users->status = Comment::STATUS_ACTIVE;
		$users->save();
		$this->renderPrint('Account Activated');
	}

	public function actionUpdateEmail($emailid, $id){
		$users = User::model()->findByPk($id);
		if($users->status == Comment::STATUS_ACTIVE){
			$users->email = $emailid;	
			$users->save();
			$this->renderPrint('Account Updated');	
		}
	}
	public function actionUpdatePassword($passwordid, $id){
		$users = User::model()->findByPk($id);
		if($users->status == Comment::STATUS_ACTIVE){
			$users->password = $passwordid;
			$users->save();
			$this->renderPrint('Account Updated');	
		}
	}
}