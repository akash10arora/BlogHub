<?php
class PostController extends Controller {

	public $_post;

	public function filters() {
		return array(
			'checkAndSetUser + view, comments, delete, update',
			'recoverAccount + recover',
			);
	}

	public function filterCheckAndSetUser($filterChain) {
		if(!$_GET['id'])
			$this->renderError("Invalid Data!");
		else {
			$this->_post = Post::model()->active()->findByPk($_GET['id']);
			if(!$this->_post)
				$this->renderError("Invalid Data!");			
		}
		$filterChain->run();
	}

	public function filterRecoverAccount($filterChain){

		if(!$_GET['id'])
			$this->renderError("Invalid Data!");
		else {

			$this->_post = Post::model()->deactivated()->findByPk($_GET['id']);
			if(!$this->_post)
				$this->renderError("Invalid Data!");			
		}
		$filterChain->run();
	}

	public function actionCreate() {
		if(isset($_POST['Posted'])) {
			$post = Post::create($_POST['Posted']);
			if(!$post->errors) {
				$this->renderSuccess(array('post_id'=>$post->id));
			} else {
				$this->renderError($this->getErrorMessageFromModelErrors($post));
			}
		} else {
			$this->renderError('Please send post data!');
		}
	}

	
	public function actionSearch($str){             //counts the number of total post  using the keyword 
		$posts = Post::model()->findAll(array('condition'=>"content LIKE :str", 'params'=>array('str'=>"%$str%")));
		$posts_data = array();
		foreach ($posts as $post){
			if($post->status==Comment::STATUS_ACTIVE){
				$posts_data[] = array('id'=>$post->id, 'content'=>$post->content);
			}
			else {
				$this->renderDeactive($post->id);
			}
		}    
		echo CJSON::encode(array('status'=>'SUCCESS', 'count_post'=>count($posts), 'posts_data'=>$posts_data,));
	}

	

	
	public function actionView($id) {
		$this->renderSuccess(array('id'=>$this->_post->id,'content'=>$this->_post->content));
	}

	public function actionComments($id) {
		$comments_data = array();
		foreach ($this->_post->comments('comments:active') as $comment) {
			$comments_data[] = array('user_id'=>$comment->user_id, 'user_name'=>$comment->user->name, 'content'=>$comment->content);
		}
		$this->renderSuccess(array('comments'=>$comments_data));
	}


	public function actionDelete($id){
		$this->_post->deactivate($id);
		$this->renderPrint(array('message'=>'Post Deleted Successfully'));
	}



	public function actionRecover($id){

		$this->_post->activate($id);
		$this->renderPrint(array('message'=>'Post Activated Successfully'));
	}



	public function actionUpdate($str, $id){

		$this->_post->content = $str;
		$this->_post->save();
		$this->renderPrint(array('Updated Successfully'));

	}

}
