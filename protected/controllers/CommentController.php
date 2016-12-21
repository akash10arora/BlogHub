<?php
class CommentController extends Controller {

  public function actionCreate() {
    if(isset($_POST['Comment'])) {
      $comment = Comment::create($_POST['Comment']);
      if(!$comment->errors) {
        $this->renderSuccess(array('post_id'=>$comment->post_id,'content'=>$comment->content,'user_id'=>$comment->user_id));
      } else {
        $this->renderError($this->getErrorMessageFromModelErrors($comment));
      }    
    } else {
      $this->renderError('Please send comment data!');
    }
  }

    public function actionComments($id) {                   //returns only the particular comment
      $post = Post::model()->findByPk($id);
      $comments = $post->comments;
      foreach ($comments as $comment) {
        
           // echo $comment->content;

            echo CJSON::encode(array('status'=>'SUCCESS', 'comment'=>$comment->content));  // content is attribute
           // echo "<br>";

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






