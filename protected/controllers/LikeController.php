<?php
class LikeController extends Controller {

	public function actionCreate() {
		if(isset($_POST['Like'])) {
			$like = Like::create($_POST['Like']);
			if(!$like->errors) {
				$this->renderSuccess(array('post_id'=>$like->post_id, 'user_id'=>$like->user_id));
			} else {
				$this->renderError($this->getErrorMessageFromModelErrors($like));
			}
		} else {
			$this->renderError('Please send like data!');
		}
	}


}   