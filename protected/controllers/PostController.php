<?php
class PostController extends Controller {

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
        foreach ($posts as $post) {
            if($post->status==1){

             $posts_data[] = array('id'=>$post->id, 'content'=>$post->content);
         }
         else {
                //echo "Account Deactivated";
         }
     }    
     
     echo CJSON::encode(array('status'=>'SUCCESS', 'count_post'=>count($posts), 'posts_data'=>$posts_data,
        ));

 }

    public function actionComments($id) {                               //returns comment on post by taking in user_id
       $post = Post::model()->findByPk($id);
       $comments = $post->comments;
       foreach ($comments as $comment) {
        if($post->status==1){
           echo CJSON::encode(array('status'=>'SUCCESS', 'user_id'=>$comment->user_id, 'content'=>$comment->content));
       }
       else {
        echo "$post->id is Account Deactivated";
    }
}
}

    public function actionDelete($id){                  //delete post

       $post = Post::model()->findByPk($id);
       $post->status = 2;
       $post->save();
   }

   public function actionRecover($id){                  //activate post

       $post = Post::model()->findByPk($id);
       $post->status = 1;
       $post->save();
   }

}
