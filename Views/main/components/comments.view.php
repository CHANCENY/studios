<?php use GlobalsFunctions\Globals;
use Modules\Comments\Comments;


@session_start();

if(Globals::method() === "POST" && empty(Globals::user())){
    Globals::redirect('login-user-at-stream-studios?destination='. Globals::uri());
}

if(empty($b) || empty($e)){
    Globals::redirect(Globals::home());
}

$comments = (new Comments(bundle:  $b, entityID: $e ))->commentInformation();

if(Globals::method() === "POST" && !empty(Globals::post('comment_post'))){
  $save = new Comments(
       comment: Globals::post('text'),
      uid: Globals::user()[0]['uid'] ?? 2,
      type: 1,
      bundle: Globals::post('b'),
      entityID: Globals::post('e')
  );
  $save->saveComment();

  $url = $b === "shows" ? "series-overview-details" : "film-overview-details";
  $url = $b === "shows" ? $url."?series-id=".Globals::get('series-id') : $url."?movie-id=".Globals::get('movie-id');
  Globals::redirect($url);
  exit;
}


if(Globals::method() === "POST" && !empty(Globals::post('comment_replies'))){
    $save = new Comments(
         comment: Globals::post('text'),
        uid: empty(Globals::post('replier')) ? 2 : intval(Globals::post('replier')),
        cid: intval(Globals::post('cid')),
        type: 1
    );
    $save->saveReply();
    $url = $b === "shows" ? "series-overview-details" : "film-overview-details";
    $url = $b === "shows" ? $url."?series-id=".Globals::get('series-id') : $url."?movie-id=".Globals::get('movie-id');
    Globals::redirect($url);
    exit;
}

?>
<!-- comments -->
<div class="col-12">
    <div class="comments">
        <ul class="comments__list"><?php $at = 0; if(!empty($comments)): foreach ($comments as $key=>$comment): ?>
            <li class="comments__item">
                <div class="comments__autor">
                    <img class="comments__avatar" src="assets/main/img/user.png" alt="">
                    <span class="comments__name"><?php $user = \User\User::loadUser($comment['comment_uid']);
                    $name = $user['firstname'] ?? null;
                    $last = $user['lastname'] ?? null;
                    echo $name. " ".$last;
                     ?></span>
                    <span class="comments__time"><?php echo (new DateTime($comment['created']))->format("d.m.Y, H:i"); //30.08.2018, 17:53 ?></span>
                </div>
                <p class="comments__text"><?php echo $comment['comment_body'] ?? null; ?></p>
                <div class="comments__actions">
                    <div class="comments__rate">
                        <button id="like<?php echo $at; ?>" entity="<?php echo $e; ?>" uid="<?php echo Globals::user()[0]['uid'] ?? 1; ?>" cid="<?php echo $comment['cid'] ?? null; ?>" type="button"><i class="icon ion-md-thumbs-up"></i><?php echo Comments::likesCount($comment['cid'], 0) ?? 0; ?></button>

                        <button id="dislike<?php echo $at; ?>" entity="<?php echo $e; ?>" uid="<?php echo Globals::user()[0]['uid'] ?? 1; ?>" cid="<?php echo $comment['cid'] ?? null; ?>" type="button"><?php echo Comments::disLikesCount($comment['cid'] ?? 0, 0); ?><i class="icon ion-md-thumbs-down"></i></button>
                    </div>

                    <button type="button"><i class="icon ion-ios-share-alt" data-toggle="collapse" href="#collapseExample<?php echo $comment['cid'] ?? null; ?>" role="button" aria-expanded="false" aria-controls="collapseExample<?php echo $comment['cid'] ?? null; ?>"></i>Reply</button>
                    <button type="button"><i class="icon ion-ios-quote"></i>Quote</button>
                </div>
                <div class="collapse" id="collapseExample<?php echo $comment['cid'] ?? null; ?>">
                    <div class="card card-body">
                        <form action="<?php echo Globals::uri(); ?>" class="form" method="POST">
                            <textarea id="text" name="text" class="form__textarea" placeholder="Add Reply"></textarea>
                            <input type="hidden" name="cid" value="<?php echo $comment['cid']; ?>">
                            <input type="hidden" name="replier" value="<?php echo Globals::user()[0]['uid'] ?? null; ?>">
                            <button type="submit" name="comment_replies" value="send" class="form__btn">Replay</button>
                        </form>
                    </div>
                </div>
            </li><?php foreach ($comment['replies'] as $reply=>$value): ?>
                  <li class="comments__item comments__item--answer">
                <div class="comments__autor">
                    <img class="comments__avatar" src="assets/main/img/user.png" alt="">
                    <span class="comments__name"><?php $user = \User\User::loadUser($value['comment_reply_uid']);
                        $name = $user['firstname'] ?? null;
                        $last = $user['lastname'] ?? null;
                        echo $name. " ".$last;
                        ?></span>
                    <span class="comments__time"><?php echo (new DateTime($value['created']))->format("d.m.Y, H:i"); //30.08.2018, 17:53 ?></span>
                </div>
                <p class="comments__text"><?php echo $value['comment_reply_body'] ?? null; ?></p>
            </li>
            <?php endforeach; ?>
        <?php $at++; endforeach; endif; ?></ul>
        <i class="d-none" id="t" data="<?php echo $at; ?>"></i>
        <form action="<?php echo Globals::uri(); ?>" class="form" method="POST">
            <textarea id="text" name="text" class="form__textarea" placeholder="Add comment"></textarea>
            <input type="hidden" name="e" value="<?php echo $e; ?>">
            <input type="hidden" name="b" value="<?php echo $b;?>">
            <button type="submit" name="comment_post" value="send" class="form__btn">Send</button>
        </form>
    </div>
</div>
<!-- end comments -->
<script src="assets/main/js/comments.js"></script>