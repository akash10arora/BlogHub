<?php
class CommentController extends Controller {

	public $_comment;

	public function filters() {
		return array(
			'checkAndSetUser + comments, delete, update',
			'recoverAccount + recover',
			);
	}

	public function filterCheckAndSetUser($filterChain) {
		if(!$_GET['id'])
			$this->renderError("Invalid Data!");
		else {
			$this->_comment = Comment::model()->active()->findByPk($_GET['id']);
			if(!$this->_comment)
				$this->renderError("Invalid Data!");			
		}
		$filterChain->run();
	}

	public function filterRecoverAccount($filterChain){

		if(!$_GET['id'])
			$this->renderError("Invalid Data!");
		else {

			$this->_comment = Comment::model()->deactivated()->findByPk($_GET['id']);
			if(!$this->_comment)
				$this->renderError("Invalid Data!");			
		}
		$filterChain->run();
	}

	public function actionCreate() {
		if(isset($_POST['Comment'])) {
			$comment = Comment::create($_POST['Comment']);
			if(!$comment->errors) {
				$this->renderSuccess(array('post_id'=>$comment->post_id, 'content'=>$comment->content, 'user_id'=>$comment->user_id));
			} else {
				$this->renderError($this->getErrorMessageFromModelErrors($comment));
			}    
		} else {
			$this->renderError('Please send comment data!');
		}
	}	
	
	public function actionTopComments($id){                                         //returns top latest comment and the persons name 
		$commentss = Comment::model()->findAll(array('condition'=>"post_id = :post_id", 'params'=>array('post_id'=>$id), 'order'=>'created_at DESC', 'limit'=>5));
		$comments_data = array();
		foreach($commentss as $comment){
			$comments_data[] = array('user_name'=>$comment->user->name, 'content'=>$comment->content);
		}
		echo CJSON::encode(array('status'=>'SUCCESS', 'Comments_information'=>$comments_data));
	}

	public function actionDelete($id){
		$this->_comment->deactivate($id);
		$this->renderPrint(array('message'=>'Post Deleted Successfully'));
	}



	public function actionRecover($id){

		$this->_comment->activate($id);
		$this->renderPrint(array('message'=>'Post Activated Successfully'));
	}



	public function actionUpdate($str, $id){

		$this->_comment->content = $str;
		$this->_comment->save();
		$this->renderPrint(array('Updated Successfully'));

	}
}






