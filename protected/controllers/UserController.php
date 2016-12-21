
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
        //empty
      }   else {

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
            'posts_data'=>$posts_data
         ));
}

/*
public function actionNewsfeeds(){

   echo "News Feeds </br>";
   //$posts = Post::model()->findAllByAttributes(array('user_id'=>$id));
   //$posts = Post::model()->findAll("user_id = :user_id", array('user_id'=>$id));
   //$posts = Post::model()->findAll(array('condition'=>"user_id = :user_id", 'params'=>array('user_id'=>$id)));
   $posts = Post::model()->findAll(array('condition'=>"user_id != :user_id", 'params'=>array('user_id'=>$id), 'order'=>'created_at DESC', 'limit'=>10));

   if(!$posts) {


   }
   else{

       $posts_data = array();

       foreach ($posts as $post) {
         $posts_data[] = array('id'=>$post->id, //'content'=>$post->content, 
         'user_name'=>$post->user->name);
}
echo CJSON::encode(array('status'=>'SUCCESS',
 'posts_data'=>$posts_data
 ));
}
}*/


public function actionProfile($id) {                //user profile by id
   $user = User::model()->findbyPK($id);
   if(!$user) {
       echo "Account does not exist.";

   }
   else {
      echo CJSON::encode(array('status'=>'SUCCESS','name'=>$user->name,'email'=>$user->email));
  }
}

  public function actionLogin($id) {
        $user = User::model()->findbyPK($id);
        if(!$user) {
            echo "Account does not exist.";
        }
        else {
        //    echo 'hi';
           echo CJSON::encode(array('id'=>$user->id,'status'=>'SUCCESS'));
       }
   }


   public function actionSearchProfile($name){
       $users = User::model()->findAllByAttributes(array('name'=>$name));
       if(!$user) {
            echo "Account does not exist.";
        }
        else{
       $users_profile = array();
       foreach($users as $user){
           $users_profile[] = array('user_id'=>$user->id, 'user_name'=>$user->name, 'email'=>$user->email);
       }
   }
       echo CJSON::encode(array('status'=>'SUCCESS', 'users_profile'=>$users_profile));
   }
 public function actionDelete($id){                  //delete user

       $users = User::model()->findByPk($id);
       $users->status = 2;
       $users->save();
   }

   public function actionRecover($id){                  //activate user

       $users = User::model()->findByPk($id);
       $users->status = 1;
       $users->save();
   }

}
